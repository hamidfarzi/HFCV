<?php

/**
 * The hfgsb gutenberg form template file
 * 
 * @package hfcv
 */

?>

<form>
    <select style="width:16%; margin:5px 0" name="tax" placeholder="Select the taxonomy">
        <option value="">Select the taxonomy</option>
        <option value="skill">Skills</option>
        <option value="client">Clients</option>
        <option value="experience-type">Experience Types</option>
    </select>
    <select style="width:16%; margin:5px 0" name="type" placeholder="Display as">
        <option value="">Display as</option>
        <option value="list">List</option>
        <option value="carousel">Carousel</option>
    </select>
    <select style="width:16%; margin:5px 0" name="contain" placeholder="Contain">
        <option value="">Contain</option>
        <option value="top">Taxonomy top level terms</option>
        <option value="all">Taxonomy all terms</option>
        <option value="sel">Selected terms</option>
        <option value="sel-child">Child terms of selected term</option>

    </select>
    <select style="width:16%; margin:5px 0" name="level" placeholder="Display level">
        <option value="">Display level?</option>
        <option value=true>Yes</option>
        <option value=false>No</option>
    </select>
    <input type="text" style="width:16%; margin:5px 0" name="cols" placeholder="Columns" />
    <input type="text" style="width:16%; margin:5px 0" name="count" placeholder="count" value="" />
    <input type="text" style="width:100%; margin:5px 0" name="terms" placeholder="The term slugs seprate with |" />

</form>