<?php 
/* Template Name: Etsy Feed */

$feedXML = simplexml_load_file('https://www.etsy.com/shop/thehighbead/rss'); // specify feed url

/* build search links with these keywords */
$colors = ['white','black','red','blue','green','aqua','yellow','orange','amber','purple','grey'];
$materials = ['bead', 'beads', 'beaded', 'beadwork', 'bead-weaving', 'wire', 'wirework', 'copper', 'silver', 'gold', 'stone', 'wood', 'steel'];
$products = ['necklace', 'bracelet', 'earrings', 'pendant', 'ring'];
$plurals = array();

function make_plural($str)
{   
    if (substr($str, -1) !== 's') :
        return '' . $str . 's';
    else :
        return $str;
    endif;
}

foreach ($products as $product) :
    array_push($plurals, make_plural($product));
endforeach;

$keyword_array = array();
$searchtag_array = array();
$keyword_array = array_merge($keyword_array, $colors, $materials, $products);
$searchtag_array = array_merge($searchtag_array, $colors, $materials, $plurals);

function build_lookup($arr, $searchtag_array)
{
    $lookup = array();
    foreach ($arr as $idx=>$tag) :
        $lookup[$tag] = $searchtag_array[$idx];
    endforeach;
    return $lookup;
}
$searchtag_lookup = build_lookup($keyword_array, $searchtag_array);


function create_array_from_text($str)
{
    $str = preg_replace("#[[:punct:]]#", "", $str);
    $str_arr = explode(" ",$str);
    return $str_arr;
}

function find_keywords($desc_arr, $keyword_arr)
{
    $found_array = array();
    foreach ($keyword_arr as $keyword) :
        if (in_array($keyword, $desc_arr) !== false) :
            // to make a set key == value
            $found_array[$keyword] = $keyword;
        endif;
    endforeach;
    return $found_array;
}

function create_search_links($st_arr, $st_lookup)
{
    $links = array();
    foreach ($st_arr as $st) :
        $search_term = $st;
        $lookup = $st_lookup[$st]; // use lookup to get plurals for 'necklaces' in text
        $open_a = '<a href="https://www.etsy.com/shop/TheHighBead?search_query=' . $search_term . '" target="_blank" >';
        $close_a = '</a>';
        $span = '<span class="tag">' . $lookup . '</span>';
        $link = $open_a . $span . $close_a;
        array_push($links, $link );
    endforeach;
    return $links;
}

function xml_to_array($xml)
{
    $arr = array();
 
    foreach ($xml->children() as $r)
    {
        $t = array();
        if(count($r->children()) == 0)
        {
            $arr[$r->getName()] = strval($r);
        }
        else
        {
            $arr[$r->getName()][] = xml_to_array($r);
        }
    }
    return $arr;
}

$feed = xml_to_array($feedXML);

$items = $feed['channel'][0]['item'];
// built up items and 
?>


<?php if (!empty($items)) : ?>
<div class="etsy-rss-feed">
    <?php foreach ($items as $item) : ?>
    <div class="etsy-rss-item">
        <a href="<?php echo $item['link']; ?>">
            <h2><?php echo $item['title']; ?></h2>
        <?php
        $d = new DOMDocument();
        $d->loadHTML($item['description']);
        $img = $d->getElementsByTagName('img')[0]->getAttribute('src');
        $price = $d->getElementsByTagName('p')[1]->nodeValue;
        $desc = $d->getElementsByTagName('p')[2]->nodeValue;
        // pass $desc to the function to return $searchlinks;
        $desc_arr = create_array_from_text($desc);
        // fine
        $words = find_keywords($desc_arr, $keyword_array);
        // not fine
        // echo '<script> console.log(' . json_encode($words[0]) . '); </script>';
        $searchlinks = create_search_links($words, $searchtag_lookup);
        // // //
        ?>
            <p class="image"><img src="<?php echo $img; ?>" alt="<?php echo $desc; ?>"></p>
            <p class="description"><?php echo $desc; ?></p>
            <p class="price"><?php echo $price; ?></p>
        </a>
        <div class="search-links">
            <p class="cta">See more: </p>
                <?php foreach ($searchlinks as $searchlink) : ?>
                    <?php echo (string)$searchlink ; ?>
                <?php endforeach; ?>
            </p>
        </div>
    </div>
    <?php endforeach; ?>
    </div>      
<?php endif; ?>

