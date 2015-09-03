var $document = $(document),
	$window = $(window),
	$History = window.History;

var Ecommerce = jQuery.extend({}, jQuery);

$document.ready(function ()
{
	Ecommerce.initialize();
});

Ecommerce.initialize = function ()
{
};

Ecommerce.reloadAutoCompleteField = function ($display_field, $hidden_field, $data)
{
	$display_field.autocomplete({
		minLength: 0,
		source: $data,
		create: function (event, ui)
		{
			return false;
		},
		focus: function (event, ui)
		{
			//Mouse over an element from the list
			$display_field.val(ui.item.label);
			return false;
		},
		select: function (event, ui)
		{
			//Triggered when an item is selected from the menu.
			Ecommerce.formRemoveFieldErrorMessage($display_field);

			$display_field.val(ui.item.label);
			$hidden_field.val(ui.item.id);

			$display_field.closest('.form-group').next('.form-group').find('input').focus();

			return false;
		},
		change: function (event, ui)
		{
			$display_field.change();
		}
	})
		.autocomplete("instance")._renderItem = function (ul, item)
	{
		return $("<li>")
			.attr("data-value", item.id)
			.append(item.label)
			.appendTo(ul);
	};
};

Ecommerce.searchInAutoComplete = function ($display_field, $hidden_field)
{
	Ecommerce.formRemoveFieldErrorMessage($display_field);

	if(typeof ($display_field.autocomplete("instance")) === 'undefined')
	{
		return false;
	}

	if($hidden_field.val().length > 0)
	{
		return false;
	}

	$.each($display_field.autocomplete('widget').children(), function ($unused_id, $html_element)
	{
		if($html_element.hasAttribute('data-value'))
		{
			$element = $($html_element);
			if($display_field.val().search(new RegExp($element.html(), 'i')) > -1)
			{
				$display_field.val($element.html());
				$hidden_field.val($element.attr('data-value'));
				return;
			}
		}
	});

	//If the user input doesn't match any autocomplete element, display the error
	if($hidden_field.val().length <= 0)
	{
		Ecommerce.formAddFieldErrorMessage($display_field, 'Please select a value from the list');
	}
};

Ecommerce.addressForm = function ($reload_subdivision, $reload_location)
{
	//Event on country, it affects state and subdivision fields
	$('#e_address_country').on('change', function ()
	{
		//reset state and city field
		$('#e_address_state').val('');
		$('#e_address_subdivision').val('');
		$('#e_address_city').val('');
		$('#e_address_location').val('');

		var $country_id = $(this).children('option:selected').val();

		if($country_id.length <= 0)
		{
			return false;
		}

		Ecommerce.addressFormReloadSubdivision($reload_subdivision, $country_id);

		return false;
	});


	//Event on state, it affects city field
	$('#e_address_state').on('focus', function ()
	{
		$this = $(this);
		//On field focus, reset the subdivision and open the existing autocomplete
		if(typeof ($this.autocomplete("instance")) !== 'undefined')
		{
			$('#e_address_subdivision').val('');
			if($this.val().length > 0)
			{
				$this.autocomplete('search', $this.val());
			}
		}

		return false;
	});

	$('#e_address_state').on('change', function ()
	{
		//On field blur, try to find the user input in the autocomplete list
		Ecommerce.searchInAutoComplete($('#e_address_state'), $('#e_address_subdivision'));

		var $country_id = $('#e_address_country').children('option:selected').val();
		var $subdivision_id = $('#e_address_subdivision').val();

		//reset city field
		if($subdivision_id != '-1')
		{
			$('#e_address_city').val('');
			$('#e_address_location').val('');
		}

		if($country_id.length <= 0 || $subdivision_id.length <= 0)
		{
			return false;
		}

		Ecommerce.addressFormReloadLocation($reload_location, $country_id, $subdivision_id)

		return false;
	});


	//Event on city, it affects location field
	$('#e_address_city').on('focus', function ()
	{
		$this = $(this);
		//On field focus, reset the subdivision and open the existing autocomplete
		if(typeof ($this.autocomplete("instance")) !== 'undefined')
		{
			$('#e_address_location').val('');
			if($this.val().length > 0)
			{
				$this.autocomplete('search', $this.val());
			}
		}

		return false;
	});

	$('#e_address_city').on('change', function ()
	{
		//On field blur, try to find the user input in the autocomplete list
		Ecommerce.searchInAutoComplete($('#e_address_city'), $('#e_address_location'));

		return false;
	});


	//Check validity of simple input
	$('#ecommerce_address').on('change', 'input[type="text"]:not(#e_address_state, #e_address_city)', function ()
	{
		$this = $(this);
		if(this.checkValidity() === true)
		{
			Ecommerce.formRemoveFieldErrorMessage($this);
		}
		else
		{
			if($this.attr('id') === 'e_address_telephone')
			{
				Ecommerce.formAddFieldErrorMessage($(this), 'Wrong format. Allowed characters are "digits", " ", ".", "-", "+"');
			}
			else
			{
				Ecommerce.formAddFieldErrorMessage($(this), 'This field is required');
			}
		}

		return false;
	});

	//Initialize the form
	$document.ready(function ()
	{
		var $country_id = $('#e_address_country').children('option:selected').val();

		if($('#e_address_state').val().length > 0)
		{
			Ecommerce.addressFormReloadSubdivision($reload_subdivision, $country_id, $('#e_address_state').val());
		}
		else
		{
			$('#e_address_country').change();
		}

		if($('#e_address_city').val().length > 0)
		{
			Ecommerce.addressFormReloadLocation($reload_location, $country_id, $('#e_address_subdivision').val(), $('#e_address_city').val());
		}
		else
		{
			$('#e_address_state').change();
		}
	});
};

Ecommerce.addressFormReloadLocation = function ($reload_location, $country_id, $subdivision_id, $default_location)
{
	$('#e_address_city').prop('disabled', true);

	$.ajax({
		url: $reload_location,
		type: 'post',
		dataType: 'json',
		data: {
			country_id: $country_id,
			subdivision_id: $subdivision_id
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			$('#e_address_city').prop('disabled', false);
		},
		success: function (data)
		{
			$container = $('#e_address_city');
			$container.html('');
			$container.prop('disabled', false);

			if(typeof ($container.autocomplete("instance")) !== 'undefined')
			{
				$container.autocomplete("destroy");
			}

			if($.isEmptyObject(data) || data.error > 0)
			{
				//an error occured, don't touch the form
				return;
			}
			else if(typeof (data.success) !== 'undefined')
			{
				//country_id and subdivision_id were valid but no location was found


				if(typeof ($default_location) !== 'undefined' && $default_location.length > 0)
				{
					$container.val($default_location);
				}
				return;
			}
			else
			{
				//country_id and subdivision_id were valid and locations were found.
				Ecommerce.reloadAutoCompleteField(
					$container,
					$('#e_address_location'),
					data
					);

				if(typeof ($default_location) !== 'undefined' && $default_location.length > 0)
				{
					$container.autocomplete().val($default_location);
				}
			}
		}
	});
};

Ecommerce.addressFormReloadSubdivision = function ($reload_subdivision, $country_id, $default_subdivision)
{
	$('#e_address_state').prop('disabled', true);

	$.ajax({
		url: $reload_subdivision,
		type: 'post',
		dataType: 'json',
		data: {
			country_id: $country_id
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			$('#e_address_state').prop('disabled', false);
		},
		success: function (data)
		{
			$container = $('#e_address_state');
			$container.html('');
			$container.prop('disabled', false);

			if(typeof ($container.autocomplete("instance")) !== 'undefined')
			{
				$container.autocomplete("destroy");
			}

			if($.isEmptyObject(data) || data.error > 0)
			{
				//an error occured, don't touch the form
				return;
			}
			else if(typeof (data.success) !== 'undefined')
			{
				//country_id was valid but no subdivision was found
				if(typeof ($default_subdivision) !== 'undefined' && $default_subdivision.length > 0)
				{
					$container.val($default_subdivision);
				}

				$('#e_address_subdivision').val('-1');
				return;
			}
			else
			{
				//country_id was valid and subdivisions were found.
				Ecommerce.reloadAutoCompleteField(
					$container,
					$('#e_address_subdivision'),
					data
					);

				if(typeof ($default_subdivision) !== 'undefined' && $default_subdivision.length > 0)
				{
					$container.autocomplete().val($default_subdivision);
					$container.trigger("autocompleteselect");
					//$container.data('ui-autocomplete')._trigger('select', 'autocompleteselect', {item:{value:$container.val()}});
				}

			}
		}
	});
};

Ecommerce.formAddFieldErrorMessage = function ($container, $message)
{
	if($('#' + $container.attr('id') + 'helpBlock').length > 0)
	{
		//a error message is already displayed
		return false;
	}

	$container.closest('.form-group').addClass('has-error');
	$container.attr('aria-describedby', $container.attr('id') + 'helpBlock');

	var error_template = _.template(
		'<span class="help-block" id="<%= helpblockId %>">' +
		'<ul class="list-unstyled">' +
		'<li>' +
		'<span class="glyphicon glyphicon-exclamation-sign"></span>' +
		'<%= message %>' +
		'</li>' +
		'</ul>' +
		'</span>'
		);

	$container.parent().append(error_template({
		helpblockId: $container.attr('id') + 'helpBlock',
		message: $message
	}));
};

Ecommerce.formRemoveFieldErrorMessage = function ($container)
{
	if($('#' + $container.attr('id') + 'helpBlock').length <= 0)
	{
		//the message isn't display yet
		return false;
	}

	$container.closest('.form-group').removeClass('has-error');
	$container.removeAttr('aria-describedby');
	$('#' + $container.attr('id') + 'helpBlock').remove();
};

Ecommerce.accountAddresses = function (route_set_billing, route_set_shipping)
{
	$document.on('click', 'td.set-billing-address, td.set-shipping-address', function ()
	{
		if($(this).find('input[type="radio"]').prop('disabled') === true)
		{
			return false;
		}

		$(this).find('input[type="radio"]').change();
		return false;
	});

	$document.on('change', 'input[name="default-billing"], input[name="default-shipping"]', function ()
	{
		if(this.checked === true)
		{
			return false;
		}

		this.checked = true;

		Ecommerce.accountAddressesSetDefaultAddress((this.name === 'default-billing') ? route_set_billing : route_set_shipping, $(this));
		return false;
	});

	$document.on('click', 'a.address-delete', function ()
	{
		$('#error_message').html('');
		$a = $(this);

		$.ajax({
			url: this.href,
			type: 'post',
			error: function (jqXHR, textStatus, errorThrown)
			{
				$('#error_message').html(jqXHR.responseText);
			},
			success: function (data)
			{
				$tr = $a.closest('tr');
				$tbody = $tr.closest('tbody');

				$tr.hide('slow', function ()
				{
					$tr.remove();

					if($tbody.children().length <= 0)
					{
						window.location.reload(true);
					}

					if(typeof data.billing === 'number')
					{
						$matching = $('#account_addresses input[name="default-billing"]').filter(function () {
							return $(this).val() == data.billing;
						});
						console.log($matching);
						$matching.prop('checked', true);
					}

					if(typeof data.shipping === 'number')
					{
						$('#account_addresses input[name="default-shipping"]').filter(function () {
							return $(this).val() == data.shipping;
						}).prop('checked', true);
					}

					Ecommerce.accountAddressesResetActiveClass();
				});
			}
		});

		return false;
	});
};

Ecommerce.accountAddressesResetActiveClass = function ()
{
	$('#account_addresses table tr').each(function ()
	{
		$(this).removeClass('active');
	});

	$('#account_addresses input[name="default-billing"]').filter(':checked').each(function ()
	{
		$(this).closest('tr').addClass('active');
	});
};

Ecommerce.accountAddressesSetDefaultAddress = function (route, $input)
{
	//disable all input radio to prevent flooding
	$('#account_addresses input').each(function ()
	{
		$(this).prop('disabled', true);
	});

	$('#error_message').html('');

	$.ajax({
		url: route,
		type: 'post',
		data: {
			address_id: $input.val()
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			$('#account_addresses input').each(function ()
			{
				$(this).prop('disabled', false);
			});

			$('#error_message').html(jqXHR.responseText);
		},
		success: function (data)
		{
			$('#account_addresses input').each(function ()
			{
				$(this).prop('disabled', false);
			});

			Ecommerce.accountAddressesResetActiveClass();
		}
	});
};

Ecommerce.attributeController = function ()
{
	$document.on('click', 'button.display-form', function()
	{
		$(this).hide('slow');
		$('#attribute_container .attribute-form').show('slow');
	});

	$document.on('submit', 'form[name="e_attr_value"]', function()
	{
		$form = $(this);
		$data = $form.serialize();
		console.log($form.serialize());

		$('#error_message').html('');
		$form.find('input').prop('disabled', true);
		$form.find('.edit-loader button').hide();
		$form.find('.edit-loader div').removeClass('hidden').show();
		$form.closest('tr').removeClass('danger').removeClass('success');

		$.ajax({
			url: $form.attr('action'),
			type: 'post',
			data: $data,
			error: function (jqXHR, textStatus, errorThrown)
			{
				$('#error_message').html(jqXHR.responseText);

				$form.find('input').prop('disabled', false);
				$form.find('.edit-loader button').show();
				$form.find('.edit-loader div').hide();
				$form.closest('tr').addClass('danger').removeClass('success');
			},
			success: function (data)
			{
				$form.find('input').prop('disabled', false);
				$form.find('.edit-loader button').show();
				$form.find('.edit-loader div').hide();
				$form.closest('tr').removeClass('danger').addClass('success');
			}
		});

		return false;
	});
}