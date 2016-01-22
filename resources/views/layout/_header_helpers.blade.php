<?php

/*
 * This function will return a semantic-ui compatible menu with a (truncated) category tree.
 */
function generateMenuItem($category, $parent=null) {

    $menuitem = "<div class='item'>";
    if ($parent){
        $menuitem .= "<i class='angle right icon'></i> ";
    }
    $menuitem .= "<a href='" . route("category", ["slug"=>$category->slug]) . "'>";
    // We'll only go down one level. So if a parent is passed to the function, we don't iterate through the category's children.
    if (count($category->children) && $parent==null){


        $menuitem .= $category->name . "</a></div>";

        foreach ($category->children as $child){
            $menuitem .= generateMenuItem($child, $parent=$category);
        }

    } else {
        $menuitem .= $category->name . "</a></div>";
    }

    return $menuitem;
}
?>