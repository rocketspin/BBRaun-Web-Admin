<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once dirname(__FILE__) . '/SVGGraph/SVGGraph.php';

class Svggraphhelper
{
    protected $graph;
    protected $settings = array(
        'stroke_colour'         => '#000',
        // 'axis_max_v'            => 100,
        'minimum_grid_spacing'  => 20,
        'pad_right'             => 20,
        'pad_left'              => 20,
        'axis_text_angle_h'     => -45,
        'axis_font_size'        => 5
    );

    public function generateBarGraph($data, $chartTitle='', $subTitle = '')
    {
        $settings = $this->settings;
        if ($chartTitle) {
            $settings['graph_title'] = $chartTitle;
        }

        if ($subTitle) {
            $settings['graph_title'] .=  "\n (" . $subTitle . ")";
        }

        $graph = new SVGGraph(300, 300, $settings);
        $graph->Colours(array('#00b582'));
        $graph->Values($data);
        return $graph->fetch('BarGraph');
    }
}
