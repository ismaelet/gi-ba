/*
 * Filters
 */
const statusFilterSelector = '.js-status-filter'
const defaultStatusFilterSelector = '[data-default-status="1"]'
const notDefaultStatusFilterSelector = '[data-default-status="0"]'
const emptyStatusFiltersSelector = '.js-empty-filter-status'
const checkAllStatusFiltersSelector = '.js-status-all'
const removeStatusFilterSelector = '.js-remove-status-filter'
const billedFilterSelector = '.js-billed-filter'
const removeBilledFilterSelector = '.js-remove-billed-filter'
const dateFilterSelector = '.js-filter-date'
const removeDateFilterSelector = '.js-remove-date-filter'

/*
 * Billed checkbox
 */
const billedCheckboxSelector = '.js-billed'

// Events
document.addEventListener('DOMContentLoaded', function () {
	const listForm = document.querySelector(listSelector)
	const emptyStatusFilters = document.querySelector(emptyStatusFiltersSelector)
	const checkAllStatusFilters = document.querySelector(checkAllStatusFiltersSelector)
	const removeStatusFilter = document.querySelector(removeStatusFilterSelector)
	const removeBilledFilter = document.querySelector(removeBilledFilterSelector)
	const removeDateFilter = document.querySelector(removeDateFilterSelector)

	// filters
	document.querySelectorAll([statusFilterSelector, billedFilterSelector].join(',')).forEach((element) => {
		element.addEventListener('click', () => {
			update_filter(element)
		})

		element.addEventListener('change', () => {
			update_filter(element)
		})
	})

	if (removeStatusFilter) {
		removeStatusFilter.addEventListener('click', () => {
			document.querySelectorAll(defaultStatusFilterSelector).forEach((element) => {
				element.checked = true
			})

			document.querySelectorAll(notDefaultStatusFilterSelector).forEach((element) => {
				element.checked = false
			})

			filter_admin_list()
		})
	}

	if (removeDateFilter) {
		removeDateFilter.addEventListener('click', () => {
			document.querySelectorAll(dateFilterSelector).forEach((element) => {
				element.value = ''
			})

			filter_admin_list()
		})
	}

	if (removeBilledFilter) {
		removeBilledFilter.addEventListener('click', () => {
			document.querySelectorAll(billedFilterSelector).forEach((element) => {
				element.checked = true
			})

			filter_admin_list()
		})
	}

	checkAllStatusFilters.addEventListener('click', () => {
		const checked = checkAllStatusFilters.checked

		document.querySelectorAll(statusFilterSelector).forEach((element) => {
			element.checked = checked

			update_filter(element)
		})

		listForm.setAttribute('method', 'GET')
		listForm.submit()
	})

	function update_filter(element) {
		if (element.classList.contains(statusFilterSelector.split('.').join(''))) {
			if (document.querySelectorAll(statusFilterSelector + ':checked').length) {
				emptyStatusFilters.checked = false
			} else {
				emptyStatusFilters.checked = true
			}
		}

		window.location.hash = ''

		filter_admin_list()
	}

	// change the billed status
	document.querySelectorAll(billedCheckboxSelector).forEach((element) => {
		element.addEventListener('change', () => {
			const billed = element.checked ? 1 : 0
			const cartId = element.value

			call_class_method('Ecommerce\\Cart', 'updateBilled', [billed], cartId)
		})
	})

	// change dates
	document.querySelectorAll(dateFilterSelector).forEach((element) => {
		element.addEventListener('change', () => {
			filter_admin_list()
		})
	})
})
