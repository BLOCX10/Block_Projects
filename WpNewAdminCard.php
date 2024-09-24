<?php

final class TestCustomAdminCard {

	private const TEST_TITLE_CARD = 'TestCard';
	private const TEST_SLUG_CARD = 'test-card';
	private const TEST_ICON_CARD = 'dashicons-feedback';
	private const TEST_POSITION_CARD = 20;

	public function __construct() {
		$this->registerTestAdminPanelCard();
	}

	private function registerTestAdminPanelCard(): void {

		add_action( 'admin_menu', function () {
			add_menu_page( self::TEST_TITLE_CARD, self::TEST_TITLE_CARD, 'read', self::TEST_SLUG_CARD, [
				$this,
				'testCardTemplate'
			], self::TEST_ICON_CARD, self::TEST_POSITION_CARD );
		} );
	}

	public static function testCardTemplate(): void
	{
		$dateFrom = '';
		$dateTo = '';
		$deliveriesValue = 'empty';
		if(isset($_GET['deliveries']) && $_GET['deliveries'] != 'empty' &&  !empty($_GET['deliveries'])){
			$deliveriesValue = $_GET['deliveries'];
		}
		$todayDate = date("Y/m/j");


		if($deliveriesValue != 'empty'){
			$the_query = new WP_Query(
				array(
					'post_status' => 'publish',
					'post_type' => 'deliveries',
					'order'     => 'DESC',
					'orderby' => 'meta_value',
					'posts_per_page' => -1,
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'test_key',
							'value' => $deliveriesValue,
							'compare' => '=',
						),
						array(
							'key' => 'test_key2',
							'value' => $todayDate,
							'compare' => '>=',
							'type' => 'CHAR'
						)
					),
				)
			);
			$queryItems = $the_query->posts;
			$queryItems = array_reverse($queryItems,true);
		}
		else{
			$the_query = new WP_Query(
				array(
					'post_status' => 'publish',
					'post_type' => 'deliveries',
					'order'     => 'DESC',
					'orderby' => 'meta_value',
					'posts_per_page' => -1,
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'data_deliveries',
							'value' => $todayDate,
							'compare' => '>=',
							'type' => 'CHAR'
						)
					),
				)
			);
			$queryItems = $the_query->posts;
		}

		$today = date('Y-m-d',);
		$dateToTime = strtotime($today);
		if(!empty($dateFrom)) {
			$dateFromTime = strtotime( $dateFrom ) - 2592000;
		}
		$controlTo = false;
		$controlFrom = false;
		if($dateTo != ''){
			$dateToTime = strtotime($dateTo);
			$controlTo = true;
		}
		if($dateFrom != ''){
			$dateFromTime = strtotime($dateFrom);
			$controlFrom = true;
		}
		?>
		<div class="test">
			<div class="test__titleBox">
				<p>Zestawienie</p>
			</div>
			<div class="test__table">
				<table class="test__tableElement"  cellspacing="0">
					<thead>
					<tr class="test__tableTr">
						<td>Lp.</td>
						<td>Data</td>
						<td>Pojazd</td>
						<td>Waga</td>
						<td>Miejsce</td>
						<td>Rozliczenie</td>
						<td>Rodzaj Rozliczenia</td>
						<td>Informacje Dodatkowe</td>
					</tr>
					</thead>
					<tbody>
					<?php
					$counter = 1;
					foreach ($queryItems as $item){
						$id = $item->ID;
						if($controlFrom && $controlTo){
							$dateSearch = true;
						}
						else{
							$dateSearch = false;
						}
						$date = get_field('test',$id,true);
						$autoNumber = get_field('test',$id,true);
						$weight = get_field('test',$id,true);
						$location = get_field('test',$id,true);
						$invoice = get_field('test',$id,true);
						$typeInvoice = get_field('test',$id,true);
						$extra = get_field('test',$id,true);
						$postTime = strtotime(get_field('test',$id,true));
						if($dateSearch){
							if(($postTime >= $dateFromTime && $postTime <= $dateToTime) || ( $dateFromTime === $dateToTime && $postTime <= $dateFromTime && $postTime >= $dateToTime)){
								?>
								<tr>
									<td><?= $counter ?></td>
									<td><?= $date ?></td>
									<td><?= $autoNumber ?></td>
									<td><?= $weight ?></td>
									<td><?= get_the_title($location) ?></td>
									<td><?= $invoice ?></td>
									<td><?= $typeInvoice ?></td>
									<td><?= $extra ?></td>
								</tr>
								<?php
								$counter++;
							}
						}
						else{
							?>
							<tr>
								<td><?= $counter ?></td>
								<td><?= $date ?></td>
								<td><?= $autoNumber ?></td>
								<td><?= $weight ?></td>
								<td><?= get_the_title($location) ?></td>
								<td><?= $invoice ?></td>
								<td><?= $typeInvoice ?></td>
								<td><?= $extra ?></td>
							</tr>
							<?php
							$counter++;
						}
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}
	public static function validateDate($date, $format = 'Y-m-d')
	{
		$dateValue = DateTime::createFromFormat($format, $date);
		return $dateValue && $dateValue->format($format) === $date;
	}
}
