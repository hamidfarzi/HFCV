const { createElement, useEffect, useState } = wp.element;
const { withSelect } = wp.data;

function ShortcodeBlockEdit({ post, attributes, setAttributes }) {
  const [formContent, setFormContent] = useState(null);
  const [formValues, setFormValues] = useState({});
  const [shortcodes, setShortcodes] = useState([]);
  const [selectedShortcode, setSelectedShortcode] = useState();

  // Function to parse the PHP-generated form into a React standard form
  const parseForm = (phpForm) => {
    const doc = new DOMParser().parseFromString(phpForm, "text/html");
    const formElements = doc.querySelectorAll(
      "form input, form select, form option, form textarea"
    );

    return Array.from(formElements).map((element, index) => {
      const { name, defaultValue, placeholder, type, style, options } = element;
      if (type === "text") {
        return createElement("input", {
          key: index,
          type: type || "text",
          name,
          style: { cssText: style.cssText || " " },
          defaultValue: formValues[name] || defaultValue || "", // Use defaultValue instead of value
          placeholder: placeholder || "",
          onChange: handleInputChange,
        });
      }
      if (type === "select-one") {
        let newOptions = [];

        for (let i = 0; i < options.length; i++) {
          let option = options[i];
          let optionValue = option.value || "";
          let optionText = option.text || "-";

          let optionElement = createElement("option", {
            key: i,
            value: optionValue,
            label: optionText,
          });
          newOptions.push(optionElement);
        }

        const selectElement = createElement(
          "select",
          {
            key: index,
            name,
            style: { cssText: style.cssText || " " },
            defaultValue: formValues[name] || defaultValue || "", // Use defaultValue instead of value
            placeholder: placeholder || "",
            onChange: handleInputChange,
          },
          newOptions
        );

        return selectElement;
      }
    });
  };

  useEffect(() => {
    if (!selectedShortcode) {
      // Fetch shortcodes from custom WordPress API endpoint
      wp.apiFetch({ path: "/hf-gsb/v1/shortcodes" })
        .then((response) => {
          setShortcodes(JSON.parse(response)); // Update the state with the list of shortcodes
        })
        .catch((error) => {
          console.error("Error fetching shortcodes:", error);
        });
    } else {
      loadShortcodeForm();
    }
  }, [selectedShortcode]);

  // Function to handle form input changes
  const handleInputChange = (event) => {
    const { name, value } = event.target;
    setFormValues((prevState) => ({
      ...prevState,
      [name]: value,
    }));
  };

  const loadShortcodeForm = () => {
    wp.apiFetch({ path: "/hf-gsb/v1/forms?sc=" + selectedShortcode })
      .then((response) => {
        // Parse the PHP-generated form into a React standard form
        const reactForm = parseForm(response);

        // Update the state with the parsed form content
        setFormContent(reactForm);
      })
      .catch((error) => {
        console.error("Error fetching the form:", error);
        setFormContent("Error fetching the form.");
      });
  };

  // Function to handle dropdown selection
  const handleDropdownChange = (event) => {
    setSelectedShortcode(event.target.value);
  };

  // Function to generate the shortcode with form values
  const generateShortcode = () => {
    const attributes = Object.entries(formValues)
      .map(([name, value]) => `${name}="${value}"`)
      .join(" ");
    const shortcode = `[HFGSB sc="${selectedShortcode}" ${attributes}]`;
    setAttributes({ shortcode }); // Save the generated shortcode to the block attributes
  };

  // Call generateShortcode whenever formValues or selectedShortcode changes
  useEffect(() => {
    if (selectedShortcode) {
      loadShortcodeForm();
      generateShortcode();
    }
  }, [formValues, selectedShortcode]);

  // Render the parsed form content with form inputs and the dropdown field
  return createElement(
    "div",
    {},
    createElement(
      "select",
      { onChange: handleDropdownChange, value: selectedShortcode },
      shortcodes.map((shortcode) =>
        createElement("option", { key: shortcode, value: shortcode }, shortcode)
      )
    ),
    createElement("br"),
    formContent !== null ? formContent : "Loading form preview...",
    createElement("div", { id: "hfgsb-shortcode" }, attributes.shortcode)
  );
}

// Wrap the editor component with withSelect to retrieve post data
const wrappedShortcodeBlockEditor = withSelect((select) => {
  return {
    post: select("core/editor").getCurrentPost(),
  };
})(ShortcodeBlockEdit);

wp.blocks.registerBlockType("hf/gutenberg-shortcode-block", {
  title: "HF Gutenberg Shortcode Block",
  category: "common",
  icon: "admin-generic",
  attributes: {
    shortcode: {
      type: "string",
    },
  },
  edit: wrappedShortcodeBlockEditor,
  save: function ({ attributes }) {
    return attributes.shortcode;
  },
});
