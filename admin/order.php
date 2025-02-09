﻿<?php include 'inc/header.php';?>
<?php include 'inc/sidebar.php';?>
<?php
$filepath = realpath(dirname(__FILE__));
include_once ($filepath.'/../classes/cart.php');
include_once ($filepath.'/../helpers/format.php');

?>
<?php 
$ct = new cart();

if(isset($_GET['shiftid'])){	
	$id = $_GET['shiftid'];
	$time = $_GET['time'];
	$time_od = $_GET['time_od'];
	$mskh = $_GET['mskh'];
	
	$shifted = $ct->shifted($id,$time,$time_od,$mskh);       
}

if(isset($_GET['delid'])){	
	$id = $_GET['delid'];
	$time = $_GET['time'];
	$mskh = $_GET['mskh'];
	$del_Shifted = $ct->del_shifted($id,$time,$mskh);
}
if(!isset($_GET['id'])){
	echo "<meta http-equiv='refresh' content='0;URL=?id=live'>";
}
$ct = new cart();
if(isset($_GET['comfirmid'])){
   $id = $_GET['comfirmid'];
   $time = $_GET['time'];
   $mskh = $_GET['mskh'];
   $shifted_comfirm = $ct->shifted_comfirm($id,$time,$mskh);
   header('Location:orderdetails.php');
}
?>


<div class="grid_10">
	<div class="box round first grid">
		<h2>Tin nhắn</h2>
		<div class="block">
			<?php 
			if (isset($shifted)){
				echo $shifted;
			}
			?>
			
			<?php 
			if (isset($del_Shifted)){
				echo $del_Shifted;
			}
			?>          
			<table class="data display datatable" id="example">
				<thead>
					<tr>
						<th>STT</th>
						<th>Ngày đặt hàng</th>
						<th>Khách hàng</th>
						<th>Địa chỉ giao hàng</th>
						<th>Trạng thái</th>
						<th>Chi tiết đơn hàng</th>
						<th>Xử lí đơn hàng</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$ct = new cart();
					$fm = new Format();
					$get_inboxcart = $ct->get_inbox_cart();
					if($get_inboxcart){
						$i = 0;
						while($result = $get_inboxcart->fetch_assoc()){
							$i++;
							?>		
							<tr class="odd gradeX">
								<td><?php echo $i; ?></td>
								<td><?php echo $fm->formatDate($result['NgayDH']) ?></td>
								<td><?php echo $result['HoTenKH'] ?></td>
								<td><?php echo $result['DiaChi'] ?></td>
								<td><?php
									if($result['TrangThaiDH'] == 1){
									echo 'Đã duyệt';
									}elseif($result['TrangThaiDH'] == 0){
									echo 'Chưa duyệt';
									}else{
									echo 'Đã nhận hàng';
									} ?>
								</td>
								<td><a href="orderdetails.php?MSKH=<?php echo $result['MSKH'] ?>&SodonDH= <?php echo $result['SoDonDH']?>&MaDC=<?php echo $result['DiaChiGH'] ?>">Xem</a></td>
								<td>
								

									<?php
									$date = date('Y-m-j');
									$newdate = strtotime ( '+3 day' , strtotime ( $date ) ) ;
									$newdate = date ( 'Y-m-j' , $newdate );
									if ($result['TrangThaiDH'] == '0') {	
										?>
										<a href="?shiftid=<?php echo $result['SoDonDH'] ?>&time=<?php echo $result['NgayDH'] ?>&time_od= <?php echo $newdate ?>&mskh=<?php echo $result['MSKH']?>">Duyệt</a>
										<?php 
									}elseif($result['TrangThaiDH'] == '1'){
										?> 
										<a href="?comfirmid=<?php echo $result['SoDonDH'] ?>&time=<?php echo $result['NgayDH']?>&mskh=<?php echo $result['MSKH'] ?>">Đã nhận</a>
										<?php 
									}elseif($result['TrangThaiDH'] == '2'){
										?>
										<?php echo 'Đã nhận'; ?>
										|| <a href="?delid=<?php echo $result['SoDonDH'] ?>&time=<?php echo $result['NgayDH']?>&mskh=<?php echo $result['MSKH']?>">Xóa</a>
										<?php 
									}
									?>
									
								</td>
							</tr>
							<?php 
						}
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		setupLeftMenu();

		$('.datatable').dataTable();
		setSidebarHeight();
	});
</script>
<?php include 'inc/footer.php';?>
