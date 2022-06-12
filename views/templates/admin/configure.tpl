{*
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div class="panel">
	<h3><i class="icon-cogs"></i> {l s='Product By Category' mod='pcposition'}</h3>
	<form class="form-horizontal" action="" method="POST">
		<div class="form-group">
			<label for="pcategory" class="col-sm-3 control-label">{l s='Select Category' mod='pcposition'}</label>
			<div class="col-sm-9">
				<div class="col-sm-8">
					<select name="pcategory" class="chosen-select" id="pcategory" onchange="this.form.submit()">
						<option>----------</option>
						{foreach $category as $ct}
							<option value="{$ct.id_category}" {if $ct.id_category == $idCat}selected="selected"{/if}>{$ct.name}</option>
						{/foreach}
					</select>
				</div>
			</div>
		</div>
	</form>
</div>
{if $products}
<div class="panel">
	<h3><i class="icon-cogs"></i> {l s='Change Position' mod='pcposition'}</h3>
    <ul id="sortable" class="row">
	{foreach $products as $cp}
    	<li id="changePositon_{$cp.id_product}" class="col-lg-2 col-sm-3 col-xs-2 {if $cp.fixed == 1}static{/if}">
			<div>
				<img src="{pcposition::getpImage($cp.id_product)}" alt="{$cp.name}" />
				<p><h5>{$cp.name}</h5></p>
			</div>
		</li>
	{/foreach}
    </ul>
</div>
<script>
  $( function() {
    $('#sortable').sortable({
		opacity : 0.610,
		update : function(){
			var positions = $(this).sortable('serialize');
			var e = 0;
			$.ajax({
				url: '',
				type: 'post',
				data: positions,
				success: function(e){
					if (e == 1)
					{
						$.toast({
							heading: "{l s='Success' mod='pcposition'}",
							text: "{l s='New position of products registered' mod='pcposition'}",
							showHideTransition: 'slide',
							icon: 'success',
							position: {
								right: 65,
								top: 20
							},
						})
					}
					else
					{
						$.toast({
							heading: "{l s='Error' mod='pcposition'}",
							text: "{l s='Failed to save new position' mod='pcposition'}",
							showHideTransition: 'slide',
							icon: 'error',
							position: {
								right: 65,
								top: 20
							},
						})
					}
				}
			});
		}
	});
  });
</script>
{/if}