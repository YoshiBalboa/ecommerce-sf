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
			$hidden_field.val('');
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
			Ecommerce.formRemoveErrorMessage($display_field);

			$display_field.val(ui.item.label);
			$hidden_field.val(ui.item.id);

			$display_field.closest('.form-group').next('.form-group').find('input').focus();

			return false;
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
	Ecommerce.formRemoveErrorMessage($display_field);

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
		Ecommerce.formAddErrorMessage($display_field, 'Please select a value from the list');
	}
};

Ecommerce.addressForm = function ($reload_subdivision, $reload_location)
{
	//Event on country, it affect state and subdivision fields
	$('#ecommerce_address_country').on('change blur', function ()
	{
		var $country_id = $(this).children('option:selected').val();

		if($country_id.length <= 0)
		{
			return false;
		}

		$('#ecommerce_address_state').prop('disabled', true);

		$.ajax({
			url: $reload_subdivision,
			type: 'post',
			dataType: 'json',
			data: {
				country_id: $country_id
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				$('#ecommerce_address_state').prop('disabled', false);
			},
			success: function (data)
			{
				$container = $('#ecommerce_address_state');
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
					$('#ecommerce_address_subdivision').val('-1');
					return;
				}
				else
				{
					//country_id was valid and subdivisions were found.
					Ecommerce.reloadAutoCompleteField(
						$container,
						$('#ecommerce_address_subdivision'),
						data
						);
				}
			}
		});

		return false;
	});


	//Event on state, it affect city and location fields
	//It's launch when country and state field are selected
	$('#ecommerce_address_state').on('change blur', function ()
	{
		//On field blur, try to find the user input in the autocomplete list
		Ecommerce.searchInAutoComplete($('#ecommerce_address_state'), $('#ecommerce_address_subdivision'));

		var $country_id = $('#ecommerce_address_country').children('option:selected').val();
		var $subdivision_id = $('#ecommerce_address_subdivision').val();

		if($country_id.length <= 0 || $subdivision_id.length <= 0)
		{
			return false;
		}

		$('#ecommerce_address_city').prop('disabled', true);

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
				$('#ecommerce_address_city').prop('disabled', false);
			},
			success: function (data)
			{
				$container = $('#ecommerce_address_city');
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
					return;
				}
				else
				{
					//country_id and subdivision_id were valid and locations were found.
					Ecommerce.reloadAutoCompleteField(
						$container,
						$('#ecommerce_address_location'),
						data
						);
				}
			}
		});

		return false;
	});


	$('#ecommerce_address_state').on('focus', function ()
	{
		//On field focus, reset the subdivision and open the existing autocomplete
		if(typeof ($(this).autocomplete("instance")) !== 'undefined')
		{
			$('#ecommerce_address_subdivision').val('');
			$(this).autocomplete('search', $(this).val());
		}

		return false;
	});


	//Initialize the form
	$document.ready(function ()
	{
		$('#ecommerce_address_country').change();
		$('#ecommerce_address_state').change();
	});
};

Ecommerce.formAddErrorMessage = function ($container, $message)
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

Ecommerce.formRemoveErrorMessage = function ($container)
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
