<?php
/**
 * lib_pthlog
 *
 * Debug Klasse
 */
class pthlog
{
	private static $_instance = null;

	private static $log_url = null;
	private static $log_html = null;
	
	private static $dom = null;
	
	private static $base_url = "http://pokerth.net/";
	
	private static $hand_cash = null;
	private static $pot_size = null;
	
	private static $result_table = null;
	private static $most_hands_table = null;
	private static $best_hands_table = null;
	private static $most_wins_table = null;
	private static $highest_wins_table = null;
	private static $longest_wins_table = null;
	private static $longest_losses_table = null;
	private static $most_bets_table = null;
	private static $most_allin_table = null;
	private static $game = null;
	private static $id_gameid = null;

	public static function process_log($url){
		require_once("simple_html_dom.php"); // 3rd party html parser
		self::$log_url = $url;

		self::$id_gameid = substr($url, strpos($url, "ID="));
		
		self::fetch_html();
		self::$dom = str_get_html(self::$log_html);
		self::fetch_hand_cash();
		self::fetch_pot_size();
		self::fetch_result_table();
		self::fetch_most_hands_table();
		self::fetch_best_hands_table();
		self::fetch_most_wins_table();
		self::fetch_highest_wins_table();
		self::fetch_longest_wins_table();
		self::fetch_longest_losses_table();
		self::fetch_most_bets_table();
		self::fetch_most_allin_table();
		self::strip_unneeded();
		self::create_game();
		return self::$game;
	}
	
	private static function strip_unneeded(){
		// <div class="menu-block">
		//<div class="menu-block">
		$header = self::$dom->find('header');
		foreach($header as $entry){
			$entry->outertext = "";
		}
		
		$menu = self::$dom->find('div.menu-block');
		foreach($menu as $entry){
			$entry->outertext = "";
		}
		
		$sidebar = self::$dom->find('div.rt-sidebar-wrapper');
		foreach($sidebar as $entry){
			$entry->outertext = "";
		}
		
		$breadcrumbs = self::$dom->find('div#rt-content-top');
		foreach($breadcrumbs as $entry){
			$entry->outertext = "";
		}
		
		$cookie = self::$dom->find('div#redim-cookiehint');
		foreach($cookie as $entry){
			$entry->outertext = "";
		}
		
		$script = self::$dom->find('script');
		foreach($script as $entry){
			$entry->outertext = "";
		}
		
		$foot = self::$dom->find('div#rt-foot-surround');
		foreach($foot as $entry){
			$entry->outertext = "";
		}
		
		$toggle = self::$dom->find('table.toggle');
		foreach($toggle as $entry){
			$entry->style = "position: relative; visibility: visible;";
			//$entry->style = "";
		}
		
		$valid = self::$dom->find('p');
		foreach($valid as $entry){
			if(strpos($entry->innertext, "This log file analysis is still") !== false){
				$entry->outertext = "";
			}
		}
		
		$select = self::$dom->find('select.game');
		foreach($select as $entry){
			$entry->outertext = "";
		}
		
		$span1 = self::$dom->find('span.icon-chevron-down');
		foreach($span1 as $entry){
			$entry->outertext = "";
		}
		
		$span2 = self::$dom->find('span.icon-print');
		foreach($span2 as $entry){
			$entry->outertext = "";
		}
		
		$expand = self::$dom->find('div#div_expand_collapse');
		foreach($expand as $entry){
			$entry->outertext = "";
		}		
		
		$stacked = self::$dom->find('table#table_course_game');
		foreach($stacked as $entry){
			$entry->children(0)->children(0)->children(0)->innertext = "";
		}
		
		$hand_cash = self::$dom->find('img[name=hand_cash]');
		foreach($hand_cash as $entry){
			$entry->src = "data:image/png;base64," . base64_encode(file_get_contents(self::$hand_cash));
		}
		
		$pot_size = self::$dom->find('img[name=pot_size]');
		foreach($pot_size as $entry){
			$entry->src = "data:image/png;base64," . base64_encode(file_get_contents(self::$pot_size));
		}
		// update dom for evtl further manipulation
		self::$dom->load(self::$dom->save());
		// update html code string after stripping
		self::$log_html = self::$dom->save();
	}
	
	private static function create_game(){
		self::$game = array(
			"html" => self::$log_html,
			"result" => self::$result_table,
			"most hands played" => self::$most_hands_table,
			"best hands" => self::$best_hands_table,
			"most wins" => self::$most_wins_table,
			"highest wins" => self::$highest_wins_table,
			"longest series of wins" => self::$longest_wins_table,
			"longest series of losses" => self::$longest_losses_table,
			"most bet/raise" => self::$most_bets_table,
			"most all in" => self::$most_allin_table,
			/*
			"pics" => array(
				"hand_cash" => base64_encode(file_get_contents(self::$hand_cash)),
				"pot_size" => base64_encode(file_get_contents(self::$pot_size))
			)
			*/
		);
	}
	
	private static function fetch_result_table(){
		$table = self::$dom->find('table', 2);
		self::$result_table = array();
		for($i=1;$i<=10;$i++)
		{
			self::$result_table[$i] = array(
				"player" => trim($table->children($i)->children(1)->innertext),
				"hand" => trim($table->children($i)->children(2)->innertext),
				"eliminated" => trim($table->children($i)->children(4)->innertext)
			);
		}
	}
	
	private static function fetch_most_hands_table(){
		$table = self::$dom->find('table', 4);
		self::$most_hands_table = array();
		for($i=1;$i<=10;$i++)
		{
			self::$most_hands_table[$i] = array(
				"player" => trim($table->children($i)->children(1)->innertext),
				"count" => trim($table->children($i)->children(2)->innertext . " " . $table->children($i)->children(3)->innertext),
				"10 to 7" => trim($table->children($i)->children(4)->innertext . " " . $table->children($i)->children(5)->innertext),
				"6 to 4" => trim($table->children($i)->children(6)->innertext . " " . $table->children($i)->children(7)->innertext),
				"3 to 1" => trim($table->children($i)->children(8)->innertext . " " . $table->children($i)->children(9)->innertext),
			);
		}
	}
	
	private static function fetch_best_hands_table(){
		$table = self::$dom->find('table', 5);
		self::$best_hands_table = array();
		for($i=1;$i<=10;$i++)
		{
			self::$best_hands_table[$i] = array(
				"cards" => trim($table->children($i)->children(1)->innertext),
				"player" => trim($table->children($i)->children(2)->innertext),
				"hand" => trim($table->children($i)->children(3)->innertext),
				"result" => trim($table->children($i)->children(4)->innertext),
			);
		}
	}
	
	private static function fetch_most_wins_table(){
		$table = self::$dom->find('table', 6);
		self::$most_wins_table = array();
		for($i=1;$i<=10;$i++)
		{
			self::$most_wins_table[$i] = array(
				"player" => trim($table->children($i)->children(1)->innertext),
				"hand" => trim($table->children($i)->children(2)->innertext . " " . $table->children($i)->children(3)->innertext),
				"amount" => trim($table->children($i)->children(4)->innertext),
			);
		}
	}

	private static function fetch_highest_wins_table(){
		$table = self::$dom->find('table', 7);
		self::$highest_wins_table = array();
		for($i=1;$i<=10;$i++)
		{
			self::$highest_wins_table[$i] = array(
				"amount" => trim($table->children($i)->children(1)->innertext),
				"player" => trim($table->children($i)->children(2)->innertext),
				"hand" => trim($table->children($i)->children(4)->innertext),
			);
		}
	}

	private static function fetch_longest_wins_table(){
		$table = self::$dom->find('table', 8);
		self::$longest_wins_table = array();
		for($i=1;$i<=10;$i++)
		{
			self::$longest_wins_table[$i] = array(
				"duration" => trim($table->children($i)->children(1)->innertext),
				"player" => trim($table->children($i)->children(2)->innertext),
				"hands" => trim($table->children($i)->children(3)->innertext . $table->children($i)->children(4)->innertext .  $table->children($i)->children(5)->innertext),
				"amount" => trim($table->children($i)->children(6)->innertext),
			);
		}
	}
	
	private static function fetch_longest_losses_table(){
		$table = self::$dom->find('table', 9);
		self::$longest_losses_table = array();
		for($i=1;$i<=10;$i++)
		{
			self::$longest_losses_table[$i] = array(
				"duration" => trim($table->children($i)->children(1)->innertext),
				"player" => trim($table->children($i)->children(2)->innertext),
				"hands" => trim($table->children($i)->children(3)->innertext . $table->children($i)->children(4)->innertext .  $table->children($i)->children(5)->innertext),
				"amount" => trim($table->children($i)->children(6)->innertext),
			);
		}
	}
	
	private static function fetch_most_bets_table(){
		$table = self::$dom->find('table', 10);
		self::$most_bets_table = array();
		for($i=1;$i<=10;$i++)
		{
			self::$most_bets_table[$i] = array(
				"player" => trim($table->children($i)->children(1)->innertext),
				"count" => trim($table->children($i)->children(2)->innertext . " " . $table->children($i)->children(3)->innertext),
			);
		}
	}
	
	private static function fetch_most_allin_table(){
		$table = self::$dom->find('table', 11);
		self::$most_allin_table = array();
		for($i=1;$i<=10;$i++)
		{
			self::$most_allin_table[$i] = array(
				"player" => trim($table->children($i)->children(1)->innertext),
				"count" => trim($table->children($i)->children(2)->innertext . " " . $table->children($i)->children(3)->innertext),
			"in preflop" => trim($table->children($i)->children(4)->innertext),
			"first 5 hands" => trim($table->children($i)->children(5)->innertext),
			"total wons" => trim($table->children($i)->children(6)->innertext),
			);
		}
	}
	
  private static function fetch_html(){
    self::$log_html = file_get_contents(self::$log_url);
  }
	
	private static function fetch_hand_cash(){
		$html = self::$log_html;
		self::$hand_cash = self::$base_url . "/log_file_analysis/charts/hand_cash.php?" . self::$id_gameid . "&stacked=0&player=0&width=736";
	}
	
	private static function fetch_pot_size(){
		$html = self::$log_html;
		self::$pot_size = self::$base_url . "/log_file_analysis/charts/pot_size.php?" . self::$id_gameid . "&stacked=0&player=0&width=736";
	}
	
	private static function fetch_css_pics($css){
		// @XXX: not yet needed!
    if (!preg_match_all(
			'/\.([a-z\d_]+)\s*\{[^{}]*url\("?([^()]+)"?\)?/i',
			$css, $arr
		)){
			return array();
		}
    return array_combine($arr[1], $arr[2]);
	}

}
