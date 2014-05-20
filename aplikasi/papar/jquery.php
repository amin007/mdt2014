<!-- khas untuk jquery dan js2 lain -->
<script type="text/javascript" src="<?php echo JQUERY ?>"></script>
<?php
if (isset($this->js)) 
{
    foreach ($this->js as $js)
    {
        echo "\n";
?>        <script type="text/javascript" src="<?php echo $js_url . $js ?>"></script><?php
    }
}
echo "\n\n";
?>

<script type="text/javascript">
$(window).load(function(){
    jQuery('.submenu').hover(function () {
        jQuery(this).children('ul').removeClass('submenu-hide').addClass('submenu-show');
    }, function () {
        jQuery(this).children('ul').removeClass('.submenu-show').addClass('submenu-hide');
    <?php // }).find("a:first").append(" &raquo; ");  ?>
    }).find("a:first").append(" &raquo;<?php //echo '&lt;i class="' . $simbol . '"&gt;&lt;/i&gt';  ?> ");

	/* ---------- Datapicker ---------- */
	$('.date-picker').datepicker();
	/* ---------- Datapicker Melayu---------- */
    $('.tarikh').datepicker(
        { "format": "yyyy-mm-dd", 
        "language": "ms", 
        "weekStart": 1,
        "autoclose": true
        });
    
     $("[rel=tooltip]").tooltip();

    // side bar
    $('.bs-docs-sidenav').affix({
        offset: {
        top: function () { return $window.width() <= 980 ? 290 : 210 }
        , bottom: 270
        }
    })
});
</script>
<?php 
$dataURL = dpt_url();
//echo '<pre>'; echo '<br>$dataURL:<br>'; print_r($dataURL); echo '</pre>';
//echo '$dataURL[1]:...' . $dataURL[1] . '<br>';
if ( isset($dataURL[1]) && $dataURL[1] != 'ubah') :?>
<link rel="stylesheet" type="text/css" href="<?php echo JS ?>filter/susun.style.css" />
<script type="text/javascript" src="<?php echo JS ?>filter/susun.application.js"></script>
<?php else :?>
<script type="text/javascript">
	function lookup(inputString) 
	{
		if(inputString.length == 0) 
		{ 
			$('#suggestions').hide();// Hide the suggestion box.
		} 
		else 
		{
			$.get("<?php echo URL ?>nama.php", {cari: ""+inputString+""}, function(data)
			{
				if(data.length > 0) 
				{ 
				$('#suggestions').show();
				$('#autoSuggestionsList').html(data); 
				}
			});
		}
	} // lookup
	
	function fill(thisValue) 
	{
		$('#inputString').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}
</script>
<style type="text/css">
	.suggestionsBox {
		position: relative;
		left: 30px;
		margin: 10px 0px 0px 0px;
		width: 600px;
		background-color: #212427;
		-moz-border-radius: 7px;
		-webkit-border-radius: 7px;
		border: 2px solid #000000;	
		color: #ffff00;
	}
	
	.suggestionList {margin: 0px;padding: 0px;}
	
	.suggestionList li {		
		margin: 0px 0px 3px 0px;
		padding: 3px;
		cursor: pointer;
	}
	
	.suggestionList li:hover {background-color: #659CD8;}
</style>
<?php
endif;
