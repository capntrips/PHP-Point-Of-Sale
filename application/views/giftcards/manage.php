<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function()
{
    init_table_sorting();
    enable_select_all();
    enable_checkboxes();
    enable_row_selection();
    enable_search('<?php echo site_url("$controller_name/suggest")?>','<?php echo $this->lang->line("common_confirm_search")?>');
    enable_delete('<?php echo $this->lang->line($controller_name."_confirm_delete")?>','<?php echo $this->lang->line($controller_name."_none_selected")?>');

    $("#low_inventory").click(function()
    {
    	$('#giftcards_filter_form').submit();
    });

    $("#is_serialized").click(function()
    {
    	$('#giftcards_filter_form').submit();
    });

    $("#no_description").click(function()
    {
    	$('#giftcards_filter_form').submit();
    });

});

function init_table_sorting()
{
	//Only init if there is more than one row
	if($('.tablesorter tbody tr').length >1)
	{
		$("#sortable_table").tablesorter(
		{
			sortList: [[1,0]],
			headers:
			{
				0: { sorter: false},
				8: { sorter: false},
				9: { sorter: false}
			}

		});
	}
}

function post_giftcard_form_submit(response)
{
	if(!response.success)
	{
		set_feedback(response.message,'error_message',true);
	}
	else
	{
		//This is an update, just update one row
		if(jQuery.inArray(response.giftcard_id,get_visible_checkbox_ids()) != -1)
		{
			update_row(response.giftcard_id,'<?php echo site_url("$controller_name/get_row")?>');
			set_feedback(response.message,'success_message',false);

		}
		else //refresh entire table
		{
			do_search(true,function()
			{
				//highlight new row
				hightlight_row(response.giftcard_id);
				set_feedback(response.message,'success_message',false);
			});
		}
	}
}

function show_hide_search_filter(search_filter_section, switchImgTag) {
        var ele = document.getElementById(search_filter_section);
        var imageEle = document.getElementById(switchImgTag);
        var elesearchstate = document.getElementById('search_section_state');
        if(ele.style.display == "block")
        {
                ele.style.display = "none";
				imageEle.innerHTML = '<img src=" <?php echo base_url()?>images/plus.png" style="border:0;outline:none;padding:0px;margin:0px;position:relative;top:-5px;" >';
                elesearchstate.value="none";
        }
        else
        {
                ele.style.display = "block";
                imageEle.innerHTML = '<img src=" <?php echo base_url()?>images/minus.png" style="border:0;outline:none;padding:0px;margin:0px;position:relative;top:-5px;" >';
                elesearchstate.value="block";
        }
}

</script>

<div id="title_bar">
	<div id="title" class="float_left"><?php echo $this->lang->line('common_list_of').' '.$this->lang->line('module_'.$controller_name); ?></div>
	<div id="new_button">
		<?php echo anchor("$controller_name/view/-1/width:$form_width",
		"<div class='big_button' style='float: left;'><span>".$this->lang->line($controller_name.'_new')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line($controller_name.'_new')));
		?>
		<?php echo anchor("$controller_name/excel_import/width:$form_width",
		"<div class='big_button' style='float: left;'><span>Excel Import</span></div>",
		array('class'=>'thickbox none','title'=>'Import Items from Excel'));
		?>
	</div>
</div>

<div id="titleTextImg" style="background-color:#EEEEEE;height:20px;position:relative;">
	<div style="float:left;vertical-align:text-top;">Search Options :</div>
	<a id="imageDivLink" href="javascript:show_hide_search_filter('search_filter_section', 'imageDivLink');" style="outline:none;">
	<img src="
	<?php echo isset($search_section_state)?  ( ($search_section_state)? base_url().'images/minus.png' : base_url().'images/plus.png') : base_url().'images/plus.png';?>" style="border:0;outline:none;padding:0px;margin:0px;position:relative;top:-5px;"></a>
</div>

<div id="table_action_header">
	<ul>
		<li class="float_left"><span><?php echo anchor("$controller_name/delete",$this->lang->line("common_delete"),array('id'=>'delete')); ?></span></li>
		<li class="float_right">
		<img src='<?php echo base_url()?>images/spinner_small.gif' alt='spinner' id='spinner' />
		<?php echo form_open("$controller_name/search",array('id'=>'search_form')); ?>
		<input type="text" name ='search' id='search'/>
		</form>
		</li>
	</ul>
</div>

<div id="table_holder">
<?php echo $manage_table; ?>
</div>
<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>