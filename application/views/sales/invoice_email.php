<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/invoice_email.css"/>
	<script src="<?php echo base_url();?>js/jquery-1.4.4.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
</head>
<body>
<?php
if (isset($error_message))
{
	echo '<h1 style="text-align: center;">'.$error_message.'</h1>';
	exit;
}
?>

<div id="page-wrap">
	<div id="header"><?php echo $this->lang->line('sales_invoice'); ?></div>
	<table id="info">
		<tr>
			<td id="logo">
		        <?php if ($this->Appconfig->get('company_logo') == '') 
		        { 
		        ?>
		        <div id="company_name"><?php echo $this->config->item('company'); ?></div>
				<?php 
				}
				else 
				{ 
				?>
				<img id="image" src="<?php echo $image_prefix. 'uploads/' . $this->config->item('company_logo'); ?>" alt="company_logo" />			
				<?php
				}
				?>
			</td>
			<td id="customer-title">
				<pre><?php if(isset($customer))
				{
					echo $customer_info;
				}
				?></pre>
			</td>
		</tr>
		<tr>
	       	<td id="company-title"><pre><?php echo $company_info; ?></pre></td>
	        <td id="meta">
	        	<table align="right">
	            <tr>
	                <td class="meta-head"><?php echo $this->lang->line('sales_invoice_number');?> </td>
	                <td><div><?php echo $invoice_number; ?></div></td>
	            </tr>
	            <tr>
	                <td class="meta-head"><?php echo $this->lang->line('common_date'); ?></td>
	                <td><div><?php echo $transaction_date; ?></div></td>
	            </tr>
	            <?php if ($amount_due > 0)
	            {
	            ?>
	            <tr>
	                <td class="meta-head"><?php echo $this->lang->line('sales_amount_due'); ?></td>
	                <td><div class="due"><?php echo to_currency($total); ?></div></td>
	            </tr>
	            <?php 
				}
	            ?>
				</table>
	        </td>
		</tr>
	</table>
	

	<table id="items">
	  <tr>
	      <th><?php echo $this->lang->line('sales_item_number'); ?></th>
	      <th><?php echo $this->lang->line('sales_item_name'); ?></th>
	      <th><?php echo $this->lang->line('sales_quantity'); ?></th>
	      <th><?php echo $this->lang->line('sales_price'); ?></th>
	      <th><?php echo $this->lang->line('sales_discount'); ?></th>
	      <th><?php echo $this->lang->line('sales_total'); ?></th>
	  </tr>
	  
	<?php
		foreach($cart as $line=>$item)
		{
		?>
			<tr class="item-row">
				<td><?php echo $item['item_number']; ?></td>
				<td class="item-name long_name"><?php echo !empty($item['description']) ? $item['description'] : $item['name']; ?></td>
				<td><?php echo $item['quantity']; ?></td>
				<td><?php echo to_currency($item['price']); ?></td>
				<td><?php echo $item['discount'] .'%'; ?></td>
				<td class="total-line"><?php echo to_currency($item['discounted_total']); ?></td>
			</tr>
		<?php
		}
		?>
		<tr>
			<td colspan="6" align="center"><?php echo '&nbsp;'; ?></td>
		</tr>
		     
	  <tr>
	      <td colspan="3" class="blank"> </td>
	      <td colspan="2" class="total-line"><?php echo $this->lang->line('sales_sub_total'); ?></td>
	      <td id="subtotal" class="total-value"><?php echo to_currency($tax_exclusive_subtotal); ?></td>
	  </tr>
	  <tr>
	      <td colspan="3" class="blank"> </td>
	      <td colspan="2" class="total-line"><?php echo $this->lang->line('sales_tax'); ?></td>
	      <td id="taxes" class="total-value"><?php echo to_currency(array_sum($taxes)); ?></td>
	  </tr>
	  <tr>
	      <td colspan="3" class="blank"> </td>
	      <td colspan="2" class="total-line"><?php echo $this->lang->line('sales_total'); ?></td>
	      <td id="total" class="total-value"><?php echo to_currency($total); ?></td>
	  </tr>
	</table>
	
	<div id="terms">
		<div id="sale_return_policy">
		 	<h5>
			 	<div><?php echo nl2br($this->config->item('payment_message')); ?></div>
			  	<div><?php echo $this->lang->line('sales_comments'). ': ' . (empty($comments) ? $this->config->item('invoice_default_comments') : $comments); ?></div>
		  	</h5>
			<?php echo nl2br($this->config->item('return_policy')); ?>
		</div>
		<div id='barcode'>
			<img src='data:image/png;base64,<?php echo $barcode; ?>' /><br>
			<?php echo $sale_id; ?>
		</div>
	</div>
</div>

</body>
</html>
