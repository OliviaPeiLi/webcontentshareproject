<?php

/******************* flot helper functions ********************/
function get_plot_row($rows, $x_column, $y_column, $options=array())
{
    $data = array();
    foreach($rows as $i=> $row)
    {
        $x = $x_column ? $row->$x_column : $i;
        $y = $row->$y_column;
        $data[] = array($x, $y);
    }

    return json_encode(array( //graph 1
                           array(                 //series 1
                               'color'=> $options['color'],
                               'data'=> $data,
                               'label'=> $options['label']
                           )
                       ));
}

function get_plot_array($rows, $x_column_name=NULL, $y_column_name, $label_column_name="")
{
    $res = "";
    foreach($rows as $i=> $row)
    {
        $x = $x_column_name ? $row->$x_column_name : $i;
        $y = $row->$y_column_name;
        $label = $row->$label_column_name;
        $res .="{label: '$label', data: [[$x,$y]]},";

    }
    return trim($res,",");
}