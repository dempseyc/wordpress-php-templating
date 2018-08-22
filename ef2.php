<?php /* Template Name: Etsy Feed */

include_once(ABSPATH.WPINC.'/rss.php'); // path to include script
$feed = fetch_rss('https://www.etsy.com/shop/thehighbead/rss'); // specify feed url
$items = array_slice($feed->items, 0, 3); // specify first and last item
?>

<?php if (!empty($items)) : ?>
    <div class="etsy-rss-feed">
        <?php foreach ($items as $item) : ?>
            <div class="etsy-rss-item">
                <a href="<?php echo $item['link']; ?>">
                    <h2><?php echo $item['title']; ?></h2>
                    <p><?php echo $item['description']; ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>