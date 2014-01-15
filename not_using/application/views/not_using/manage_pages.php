<ul>
    <? foreach($list_pages_made as $key=>$item)
        {?>
            <li><a href="/interests.uri_name/<?=$item['page_id'];?>"><?=$item['page_name'];?></a>
            <? if($item['page_id'] != $this->session->userdata('page_id')) 
            {?>
                <a href="/switch_to_page/<?=$item['page_id'];?>">switcher</a></li> 
            <? }
        }
    ?>
</ul>
