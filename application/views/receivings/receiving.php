<?php $this->load->view("partial/header"); ?>

<div id="page_title" style="margin-bottom:8px;"><?php echo $this->lang->line('recvs_register'); ?></div>

<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}
?>



<div id="register_wrapper">
	<?php echo form_open("receivings/change_mode",array('id'=>'mode_form')); ?>
    <span><?php echo $this->lang->line('recvs_mode') ?></span>
	<?php echo form_dropdown('mode',$modes,$mode,'onchange="$(\'#mode_form\').submit();"'); ?>
    

    <span><?php echo $this->lang->line('recvs_stock_source') ?></span>
    <?php echo form_dropdown('stock_source',$stock_locations,$stock_source,'onchange="$(\'#mode_form\').submit();"'); ?>
    <?php 
        $opacity_style='';
        if($mode!='requisition')
        {
            $opacity_style = 'style="opacity:0.0;"';
        }
    ?>
    <span <?php echo $opacity_style; ?> > <?php echo $this->lang->line('recvs_stock_destination') ?></span>
    <?php echo form_dropdown('stock_deatination',$stock_locations,$stock_destination,'onchange="$(\'#mode_form\').submit();" '.$opacity_style); ?>        
    
	</form>
	<?php echo form_open("receivings/add",array('id'=>'add_item_form')); ?>
	<label id="item_label" for="item">

	<?php
	if($mode=='receive' or $mode=='requisition')
	{
		echo $this->lang->line('recvs_find_or_scan_item');
	}
	else
	{
		echo $this->lang->line('recvs_find_or_scan_item_or_receipt');
	}
	?>
	</label>
<?php echo form_input(array('name'=>'item','id'=>'item','size'=>'40'));?>
<div id="new_item_button_register" >
		<?php echo anchor("items/view/-1/width:360",
		"<div class='small_button'><span>".$this->lang->line('sales_new_item')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line('sales_new_item')));
		?>
	</div>

</form>

<!-- Receiving Items List -->

<table id="register">
<thead>
<tr>
    <?php
    if($mode=='requisition')
    {
    ?>
        <th style="width:11%;"><?php echo $this->lang->line('common_delete'); ?></th>
        <th style="width:30%;"><?php echo $this->lang->line('recvs_item_name'); ?></th>
        <th style="width:11%;"><?php echo $this->lang->line('reqs_quantity'); ?></th>        
        <th style="width:25%;"><?php echo $this->lang->line('reqs_related_item'); ?></th>
        <th style="width:11%;"><?php echo $this->lang->line('reqs_unit_quantity'); ?></th>
        <th style="width:15%;"><?php echo $this->lang->line('reqs_unit_quantity_total'); ?></th>
        <th style="width:11%;"><?php echo $this->lang->line('recvs_edit'); ?></th>
    <?php
    }
    else
    {
    ?>
        <th style="width:11%;"><?php echo $this->lang->line('common_delete'); ?></th>
        <th style="width:30%;"><?php echo $this->lang->line('recvs_item_name'); ?></th>
        <th style="width:11%;"><?php echo $this->lang->line('recvs_cost'); ?></th>
        <th style="width:11%;"><?php echo $this->lang->line('recvs_quantity'); ?></th>
        <th style="width:11%;"><?php echo $this->lang->line('recvs_discount'); ?></th>
        <th style="width:15%;"><?php echo $this->lang->line('recvs_total'); ?></th>
        <th style="width:11%;"><?php echo $this->lang->line('recvs_edit'); ?></th>
    <?php
    }
    ?>
</tr>
</thead>
<tbody id="cart_contents">
<?php
if(count($cart)==0)
{
?>
<tr><td colspan='7'>
<div class='warning_message' style='padding:7px;'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
</tr></tr>
<?php
}
else
{
	foreach(array_reverse($cart, true) as $line=>$item)
	{
	    if($mode == 'requisition')
        {
            echo form_open("receivings/edit_item_unit/$line");
?>     
            <tr>
            <td><?php echo anchor("receivings/delete_item/$line",'['.$this->lang->line('common_delete').']');?></td>
            <td style="align:center;"><?php echo $item['name']; ?><br /> [<?php echo $this->Item->get_info($item['item_id'])->quantity; ?> in stock]</td>

            <td>
<?php
            echo form_input(array('name'=>'quantity','value'=>$item['quantity'],'size'=>'2'));
?>
            </td>
            <td><?php echo $item['related_item']; ?></td>
            <td><?php echo $item['unit_quantity']; ?></td>        
            <td><?php echo $item['unit_quantity']*$item['quantity']; ?></td>
            <td><?php echo form_submit("edit_item_unit", $this->lang->line('sales_edit_item'));?></td>
            </tr>
            </form>
<?php 
        }
        else 
        {
            echo form_open("receivings/edit_item/$line");
		
?>
		    <tr>
		    <td><?php echo anchor("receivings/delete_item/$line",'['.$this->lang->line('common_delete').']');?></td>
		    <td style="align:center;"><?php echo $item['name']; ?><br />

<?php
			echo $item['description'];
      		echo form_hidden('description',$item['description']);
?>
		    <br />

<?php       if ($items_module_allowed)
		    {
?>
			     <td><?php echo form_input(array('name'=>'price','value'=>$item['price'],'size'=>'6'));?></td>
<?php
		    }
		    else
		    {
?>
			     <td><?php echo $item['price']; ?></td>
			     <?php echo form_hidden('price',$item['price']); ?>
<?php
		    }
?>
		    <td>
<?php
            echo form_input(array('name'=>'quantity','value'=>$item['quantity'],'size'=>'2'));
?>
		    </td>
		    <td><?php echo form_input(array('name'=>'discount','value'=>$item['discount'],'size'=>'3'));?></td>
		    <td><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
		    <td><?php echo form_submit("edit_item", $this->lang->line('sales_edit_item'));?></td>
		    </tr>
		    </form>
<?php
        }	
    }
}
?>
</tbody>
</table>
</div>

<!-- Overall Receiving -->

<div id="overall_sale">
	<?php
	if(isset($supplier))
	{
		echo $this->lang->line("recvs_supplier").': <b>'.$supplier. '</b><br />';
		echo anchor("receivings/delete_supplier",'['.$this->lang->line('common_delete').' '.$this->lang->line('suppliers_supplier').']');
	}
	else
	{
		echo form_open("receivings/select_supplier",array('id'=>'select_supplier_form')); ?>
		<label id="supplier_label" for="supplier"><?php echo $this->lang->line('recvs_select_supplier'); ?></label>
		<?php echo form_input(array('name'=>'supplier','id'=>'supplier','size'=>'30','value'=>$this->lang->line('recvs_start_typing_supplier_name')));?>
		</form>
		<div style="margin-top:5px;text-align:center;">
		<h3 style="margin: 5px 0 5px 0"><?php echo $this->lang->line('common_or'); ?></h3>
		<?php echo anchor("suppliers/view/-1/width:350",
		"<div class='small_button' style='margin:0 auto;'><span>".$this->lang->line('recvs_new_supplier')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line('recvs_new_supplier')));
		?>
		</div>
		<div class="clearfix">&nbsp;</div>
		<?php
	}
	?>

    <?php
        if($mode != 'requisition')
        {
            
      ?>
	<div id='sale_details'>
		<div class="float_left" style='width:55%;'><?php echo $this->lang->line('sales_total'); ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo to_currency($total); ?></div>
	</div>
	
	<?php
    }
    ?>
    
	<?php
	if(count($cart) > 0)
	{
	    if($mode == 'requisition')
        {
    ?>
    
    <div  style='border-top:2px solid #000;' />
    <div id="finish_sale">
        <?php echo form_open("receivings/requisition_complete",array('id'=>'finish_sale_form')); ?>
        <br />
        <label id="comment_label" for="comment"><?php echo $this->lang->line('common_comments'); ?>:</label>
        <?php echo form_textarea(array('name'=>'comment','value'=>'','rows'=>'4','cols'=>'23'));?>
        <br /><br />
        
        <?php echo "<div class='small_button' id='finish_sale_button' style='float:right;margin-top:5px;'><span>".$this->lang->line('recvs_complete_receiving')."</span></div>";
        ?>
        </div>
        </form>    
        <?php echo form_open("receivings/cancel_receiving",array('id'=>'cancel_sale_form')); ?>
        <div class='small_button' id='cancel_sale_button' style='float:left;margin-top:5px;'>
        <span>Cancel </span>
        </div>
        </form>
     </div>
    <?php
        }
        else
        {
	?>
	<div id="finish_sale">
		<?php echo form_open("receivings/complete",array('id'=>'finish_sale_form')); ?>
		<br />
		<label id="comment_label" for="comment"><?php echo $this->lang->line('common_comments'); ?>:</label>
		<?php echo form_textarea(array('name'=>'comment','value'=>'','rows'=>'4','cols'=>'23'));?>
		<br /><br />
		<table width="100%"><tr><td>
		<?php
			echo $this->lang->line('sales_payment').':   ';?>
		</td><td>
		<?php
		    echo form_dropdown('payment_type',$payment_options);?>
        </td>
        </tr>

        <tr>
        <td>
        <?php
			echo $this->lang->line('sales_amount_tendered').':   ';?>
		</td><td>
		<?php
		    echo form_input(array('name'=>'amount_tendered','value'=>'','size'=>'10'));
		?>
        </td>
        </tr>

        </table>
        <br />
		<?php echo "<div class='small_button' id='finish_sale_button' style='float:right;margin-top:5px;'><span>".$this->lang->line('recvs_complete_receiving')."</span></div>";
		?>
		</div>

		</form>

	    <?php echo form_open("receivings/cancel_receiving",array('id'=>'cancel_sale_form')); ?>
			    <div class='small_button' id='cancel_sale_button' style='float:left;margin-top:5px;'>
					<span>Cancel </span>
				</div>
        </form>
	</div>
	<?php
	}
}
	?>

</div>
<div class="clearfix" style="margin-bottom:30px;">&nbsp;</div>


<?php $this->load->view("partial/footer"); ?>


<script type="text/javascript" language="javascript">
$(document).ready(function()
{
    $("#item").autocomplete('<?php echo site_url("receivings/item_search"); ?>',
    {
    	minChars:0,
    	max:100,
       	delay:10,
       	selectFirst: false,
    	formatItem: function(row) {
			return row[1];
		}
    });

    $("#item").result(function(event, data, formatted)
    {
		$("#add_item_form").submit();
    });

	$('#item').focus();

	$('#item').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_item_name'); ?>");
    });

	$('#item,#supplier').click(function()
    {
    	$(this).attr('value','');
    });

    $("#supplier").autocomplete('<?php echo site_url("receivings/supplier_search"); ?>',
    {
    	minChars:0,
    	delay:10,
    	max:100,
    	formatItem: function(row) {
			return row[1];
		}
    });

    $("#supplier").result(function(event, data, formatted)
    {
		$("#select_supplier_form").submit();
    });

    $('#supplier').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('recvs_start_typing_supplier_name'); ?>");
    });

    $("#finish_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("recvs_confirm_finish_receiving"); ?>'))
    	{
    		$('#finish_sale_form').submit();
    	}
    });

    $("#cancel_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("recvs_confirm_cancel_receiving"); ?>'))
    	{
    		$('#cancel_sale_form').submit();
    	}
    });


});

function post_item_form_submit(response)
{
	if(response.success)
	{
		$("#item").attr("value",response.item_id);
		$("#add_item_form").submit();
	}
}

function post_person_form_submit(response)
{
	if(response.success)
	{
		$("#supplier").attr("value",response.person_id);
		$("#select_supplier_form").submit();
	}
}

</script>