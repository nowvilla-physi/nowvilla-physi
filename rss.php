<?php

$posts =[];

$zenn =  simplexml_load_string(file_get_contents('https://zenn.dev/web_tips/feed'));

foreach ($zenn->channel->item as $item) {
    $posts[]  = [
        'title' => $item->title.'',
        'date' => date('Y-m-d H:i:s', strtotime($item->pubDate.'')),
        'type' => 'zenn',
        'url' => $item->link.''
    ];
}

$sort_arr = array_map( "strtotime", array_column($posts, "date") );
array_multisort($sort_arr, SORT_DESC, $posts ) ;

$dist_md = '';

foreach (array_splice($posts, 0, 5) as $post) {
    $dist_md.= "- ![](icon/${post['type']}.png) [${post['title']}](${post['url']})\n";
}

file_put_contents('README.md', preg_replace('/<!--\[START POSTS\]-->.*<!--\[END POSTS\]-->/s', "<!--[START POSTS]-->\n${dist_md}<!--[END POSTS]-->", file_get_contents('README.md')));
