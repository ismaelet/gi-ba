/**
 * Inputs
 */
const noSpaceSelector = '.js-input-no-space'

/*
 * Ajax search
 */
const ajaxSearchSelector = '.js-ajax-search'
const ajaxSearchIndexInputSelector = '.js-ajax-search__index-input'
const ajaxSearchVisibleInputSelector = '.js-ajax-search__visible-input'
const ajaxSearchDropdownSelector = '.js-ajax-search__dropdown'
const ajaxSearchItemListSelector = '.js-ajax-search__items'
const ajaxSearchItemSelector = '.js-ajax-search__item'

/*
 * Ajax search support functions
 */
function make_searched_text_bold(text, search) {
	let pattern = new RegExp('(' + search + ')', 'ig')
	return text.replace(pattern, '<strong>$1</strong>')
}

document.addEventListener('DOMContentLoaded', function () {
	/**
	 * Ajax search
	 */
	// trigger search
	document.querySelectorAll(ajaxSearchVisibleInputSelector).forEach((ajaxSearchVisibleInput) => {
		ajaxSearchVisibleInput.addEventListener('keyup', async (event) => {
			if (!['ArrowUp', 'ArrowDown'].includes(event.key)) {
				// setup
				const searchBlock = ajaxSearchVisibleInput.closest(ajaxSearchSelector)
				const dropdownElement = searchBlock.querySelector(ajaxSearchDropdownSelector)
				const itemListElement = searchBlock.querySelector(ajaxSearchItemListSelector)
				const table = searchBlock.dataset.search
				const searchFields = searchBlock.dataset.searchFields.split(',')
				const indexField = searchBlock.dataset.indexField
				const visibleField = searchBlock.dataset.visibleField
				const responseFields = [indexField, visibleField]
				const search = ajaxSearchVisibleInput.value
				const filter = searchBlock.dataset.filter

				// response
				let response = await search_table(table, responseFields, searchFields, search, filter, visibleField, 'asc')

				itemListElement.innerHTML = ''

				if (response.length) {
					response.forEach((result) => {
						const indexValue = result[indexField]
						const visibleValue = result[visibleField].replaceAll('"', '&quot;')
						const itemText = make_searched_text_bold(visibleValue, search)

						itemListElement.insertAdjacentHTML(
							'beforeend',
							'<p class="ajax-search__item ' +
								ajaxSearchItemSelector +
								'" data-index-value="' +
								indexValue +
								'" data-visible-value="' +
								visibleValue +
								'" tabindex="-1">' +
								itemText +
								'</p>'
						)
					})

					for (const searchItem of itemListElement.children) {
						searchItem.addEventListener('click', () => {
							ajax_search_item_click(searchItem)
						})

						searchItem.addEventListener('keydown', (event) => {
							ajax_search_item_keydown(searchItem, event)
						})
					}

					dropdownElement.classList.remove('hidden')
				} else {
					dropdownElement.classList.add('hidden')
				}
			}
		})

		// keydown in the search input
		document.querySelectorAll(ajaxSearchVisibleInputSelector).forEach((element) => {
			element.addEventListener('keydown', (event) => {
				const searchBlock = element.closest(ajaxSearchSelector)
				const itemList = searchBlock.querySelector(ajaxSearchItemListSelector)
				const indexInput = searchBlock.querySelector(ajaxSearchIndexInputSelector)

				const eventKey = event.key

				if (eventKey == 'ArrowDown' || eventKey == 'ArrowUp') {
					// select the an item
					const item = itemList.querySelector(eventKey == 'ArrowDown' ? 'p:first-child' : 'p:last-child')

					item.classList.add('focused')
					item.focus()
				} else {
					// clear the index value on keydown
					indexInput.value = ''
				}
			})
		})

		// hide dropdowns when clicking
		document.addEventListener('click', () => {
			document.querySelectorAll(ajaxSearchDropdownSelector).forEach((element) => {
				element.classList.add('hidden')
			})
		})

		// click on a searched item
		function ajax_search_item_click(searchItem) {
			const searchBlock = searchItem.closest(ajaxSearchSelector)
			const dropdownElement = searchBlock.querySelector(ajaxSearchDropdownSelector)
			const indexInput = searchBlock.querySelector(ajaxSearchIndexInputSelector)
			const visibleInput = searchBlock.querySelector(ajaxSearchVisibleInputSelector)
			const hiddenValue = searchItem.dataset.indexValue
			const visibleValue = searchItem.dataset.visibleValue

			indexInput.value = hiddenValue
			visibleInput.value = visibleValue
			indexInput.dispatchEvent(changeEvent)
			visibleInput.dispatchEvent(changeEvent)

			if (visibleInput.title !== undefined) {
				visibleInput.title = visibleValue
			}

			dropdownElement.classList.add('hidden')
		}

		// click on a searched item
		function ajax_search_item_keydown(searchItem, event) {
			const eventKey = event.key
			const nextItem = searchItem.nextSibling
			const previousItem = searchItem.previousSibling

			event.preventDefault()

			if (eventKey == 'ArrowDown') {
				if (nextItem) {
					searchItem.classList.remove('focused')
					nextItem.classList.add('focused')
					nextItem.focus()
				}
			} else if (eventKey == 'ArrowUp') {
				if (previousItem) {
					searchItem.classList.remove('focused')
					previousItem.classList.add('focused')
					previousItem.focus()
				}
			} else if (eventKey == 'Enter') {
				searchItem.click()
			}
		}
	})

	/**
	 * Inputs
	 */
	// input with no spaces allowed
	document.querySelectorAll(noSpaceSelector).forEach((element) => {
		element.addEventListener('keydown', (event) => {
			const eventKey = event.key

			if (eventKey == ' ') {
				event.preventDefault()

				return false
			}
		})
	})
})

/*
CONVERT
$(document).ready(function () {
	// clear error messages
	$('.clear-error').keypress(function () {
		$('.inp.err').hide()
	})
	$('.clear-error').click(function () {
		$('.inp.err').hide()
	})

	// no spance inputs
	$('.inp-nospace').keypress(function (e) {
		if (e.which === 32) return false
	})

	// price inputs
	$('.inp-price input').blur(function () {
		let val = $(this).val()

		if (val !== '') {
			val = parseFloat(val)

			$(this).val(val.toFixed(2))
		}
	})

	/*
	 * Required checkboxes
	 *
	$('.js-required-cbx').on('change', function () {
		const self = $(this)
		self.parents('.js-cbx-container').find('.js-required-cbx').prop('checked', false)
		self.prop('checked', true)
	})

	// file upload
	$('.replace-img').click(function () {
		this.nextElementSibling.click()
	})

	$('.upload-on-change').on('change', function () {
		const form = this.closest('form')

		form.classList.add('loading')
		form.submit()
	})

	// fancy inputs
	$('.fancy-input .inp').on('input keyup', function () {
		const self = $(this)

		if (self.val()) {
			self.parent().removeClass('not-filled')
		} else {
			self.parent().addClass('not-filled')
		}
	})

	// dependent selector
	$('.sel.affect').each(function () {
		selector_affect($(this))
	})
	$('.sel.affect').on('change', function () {
		selector_affect($(this))
	})

	function selector_affect(selector) {
		const affected = selector.parent().find('.sel[name="' + selector.data('affect') + '"]')
		const field_id = affected.data('id')
		const field_show = affected.data('show')

		affected.attr('disabled', false).empty()

		$.ajax({
			type: 'POST',

			url: affected.data('root') + '/system/modules/ajax/search-selector.php',

			data: { tbl: affected.data('tbl'), filter: affected.data('filter'), filter_val: selector.val(), field_id: field_id, field_show: field_show },

			success: function (data) {
				const options = $.parseJSON(data)

				for (var i = 0; i < options.length; i++) {
					const option = options[i]

					affected.append('<option value="' + option[field_id] + '" selected>' + option[field_show] + '</option>')
				}
			},
		})
	}

	/*
	 * Ajax search
	 *

	

	/*
	 * Input submit
	 *
	$('.js-submit-on-change').on('change', function () {
		$(this).parents('form').submit()
	})
})
*/

/*
CONVERT

$(document).ready(function () {
	$('.js-playable-video-overlay').click(function () {
		const videoWrap = $(this).parent('.js-playable-video-wrap')
		const video = videoWrap.find('.js-playable-video')

		videoWrap.addClass('is-playing')
		video.trigger('play').attr('controls', true)
	})
})
*/

/*
CONVERT
$(document).ready(function() {

	$('.file-inp').on('change', function() {

		$(this).parents('.file-form').find('.file-submit').click();

	});

	$('.file-form').submit(function() {

		var self = $(this);

		self.find('.file-cancel').hide();
		self.find('.file-submit').hide();
		self.find('.file-load').show();
		self.find('.file-stick').show().css('opacity', 1);

	});
	*/
