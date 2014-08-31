<?php
function e($s, $attr = FALSE) {
	echo htmlSpecialChars($s, $attr ? ENT_QUOTES : ENT_NOQUOTES);
}
function is_image($file) {
	return !is_dir($file) && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), array('jpg', 'jpeg', 'png', 'gif'));
}
function name($file) {
	echo trim(preg_replace_callback('/(^|\\-)+(.)/', function ($match) { return ' '.strtoupper($match[2]); }, pathinfo($file, PATHINFO_FILENAME)));
}

?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="robots" content="index, follow, all">

	<title>Trolling Images | Comics</title>

	<link href="bootstrap.min.css" type="text/css" rel="stylesheet" />
	<style>
		.container { margin-top: 40px; text-align:center; width:1000px; }
		.container .face { display:block; float:left; width: 130px; height: 130px; margin: 10px 10px 0 0; text-align:center; }
		.container a { display:block; width: 130px; height: 100px; }
		.container a img { max-height:100%; max-width:100%; }
		.search-query { width: 600px; height:30px; font-size: 20px; text-align:center; }
	</style>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="bootstrap.min.js"></script>
</head>
<body>



	<div class="container">

		<form class="well form-search">
			<input type="text" id="searchQuery" class="input-medium search-query" placeholder="Search in images.." />
		</form>

	<?php foreach (glob(__DIR__ . '/images/*') as $file): if(!is_image($file)) continue ?>
		<div class="face">
			<a href="https://raw.githubusercontent.com/Kcko/Trolling/master/images/<?php e(basename($file)) ?>">
				<img src="images/<?php e(basename($file)) ?>" alt="<?php e(basename($file)) ?>">
			</a>
			<?php name($file) ?>
		</div>
	<?php endforeach ?>
	</div>
	<script>
		$(function () {
			var Trolling = {
				displayRandom: 210,
				carouselDelay: 2000,
				Trolling: $('.face'),
				input: $('#searchQuery'),
				init: function () {
					this.Trolling.hide();
					this.input.on('keyup', $.proxy(this.search, this));
					this.input.focus();
					this.createIndex();
					this.carouselPlay();
				},
				index: {},
				createIndex: function () {
					var me = this;
					this.Trolling.each(function () {
						me.index[$.trim($(this).text()).toLowerCase()] = $(this);
					});
				},
				search: function () {
					var searching = $.trim(this.input.val()).toLowerCase();
					if (searching == "") {
						this.Trolling.hide();
						this.carouselPlay();

					} else {
						this.carouselStop();

						$.each(this.index, function (key, val) {
							if (key.indexOf(searching) != -1) {
								val.show();
							} else {
								val.hide();
							}
						});
					}
				},
				carouselTimer: null,
				carouselPlay: function () {
					if (this.carouselTimer) {
						this.carouselStop();
					}

					this.carouselTimer = setInterval($.proxy(this.carousel, this), this.carouselDelay);
					this.randomTrolling(this.displayRandom).show();
				},
				carouselStop: function () {
					clearInterval(this.carouselTimer);
					this.carouselTimer = false;
					this.Trolling.stop();
					this.Trolling.css({opacity:1});
					this.Trolling.hide();
				},
				carousel: function () {
					var hidden = this.randomHidden();
					var visible = this.randomVisible();

					visible.before(hidden);
					visible.fadeOut('slow', $.proxy(function () {
						hidden.fadeIn('slow');
					}, this));
				},
				randomVisible: function (){
					return this.randomTrolling(1, this.Trolling.find(':visible')).closest('.face');
				},
				randomHidden: function (){
					return this.randomTrolling(1, this.Trolling.find(':hidden')).closest('.face');
				},
				randomTrolling: function (num, Trolling) {
					if (typeof num === 'undefined') {
						num = 1;
					}
					if (typeof Trolling === 'undefined') {
						Trolling = this.Trolling;
					}

					var max = Trolling.length - num;
					var start = Math.floor(Math.random() * max);
					return Trolling.slice(start, num + start);
				}
			};

			Trolling.init();
		});
	</script>

</body>
</html>
