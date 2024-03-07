const listSelector = '.js-list-form'
const sortSelector = '.js-list-sort'
const fieldSelector = '.js-list-field'
const filterSelector = '.js-list-filter'
const searchBarSelector = '.js-search-bar'
const searchBarSearchSelector = '.js-search'
const searchBarInputSelector = '.js-search-input'
const searchBarCancelSelector = '.js-search-cancel'

function filter_admin_list() {
	const list = document.querySelector(listSelector)

	list.setAttribute('method', 'GET')
	list.submit()
}

document.addEventListener('DOMContentLoaded', function () {
	const searchBar = document.querySelector(searchBarSelector)
	const searchBarInput = document.querySelector(searchBarInputSelector)
	const searchBarCancel = document.querySelector(searchBarCancelSelector)

	// search bar
	searchBarInput.addEventListener('focus', () => {
		searchBar.classList.add('active')
	})

	searchBarInput.addEventListener('blur', () => {
		searchBar.classList.remove('active')
	})

	if (searchBarCancel) {
		searchBarCancel.addEventListener('click', () => {
			searchBarInput.value = ''
		})
	}

	// list sort
	document.querySelectorAll(sortSelector).forEach((element) => {
		element.addEventListener('click', () => {
			element.querySelectorAll(fieldSelector).forEach((element) => {
				element.checked = true
			})
		})
	})

	// filters <- has to trigger after everything else
	document.querySelectorAll(filterSelector).forEach((element) => {
		element.addEventListener('click', () => {
			filter_admin_list()
		})
	})
})
