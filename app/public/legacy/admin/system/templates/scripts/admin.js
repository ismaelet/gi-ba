document.addEventListener('DOMContentLoaded', function () {
	const menuSelector = '.js-menu'
	const menuCategorySelector = '.js-menu-category'
	const toggleMenuSelector = '.js-toggle-menu'
	const openMenuSelector = '.js-open-menu'
	const openMenuCategorySelector = '.js-open-menu-category'

	document.querySelectorAll(openMenuCategorySelector).forEach((element) => {
		element.addEventListener('click', () => {
			const category = element.closest(menuCategorySelector)

			toggle_category(category)
		})
	})

	document.querySelector(openMenuSelector).addEventListener('click', () => {
		open_menu()
	})

	document.querySelector(openMenuSelector).addEventListener('mouseenter', () => {
		open_menu()
	})

	document.querySelector(openMenuSelector).addEventListener('mouseleave', () => {
		close_menu()
	})

	document.querySelector(toggleMenuSelector).addEventListener('click', () => {
		toggle_menu()
	})

	function toggle_category(menuCategory) {
		if (!menuCategory.classList.contains('active')) {
			open_category(menuCategory)
		} else {
			close_category(menuCategory)
		}
	}

	function open_category(thisMenuCategory) {
		document.querySelectorAll(menuCategorySelector).forEach((menuCategory) => {
			close_category(menuCategory)
		})

		thisMenuCategory.classList.add('active')
	}

	function close_category(menuCategory) {
		menuCategory.classList.remove('active')
	}

	function toggle_menu() {
		if (!document.querySelector(menuSelector).classList.contains('open')) {
			open_menu()
		} else {
			close_menu()
		}
	}

	function open_menu() {
		document.querySelector(menuSelector).classList.add('open')
	}

	function close_menu() {
		document.querySelector(menuSelector).classList.remove('open')
	}
})
