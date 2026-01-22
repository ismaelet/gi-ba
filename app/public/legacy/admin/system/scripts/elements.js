/**
 * Modals
 */
const openModalSelector = '.js-open-modal'
const closeModalSelectors = '.modal, .modal__close, .js-close-modal'

document.addEventListener('DOMContentLoaded', function () {
	/**
	 * Modals
	 */
	document.querySelectorAll(openModalSelector).forEach((element) => {
		element.addEventListener('click', () => {
			const target = element.dataset.target
			const modal = document.querySelector('#' + target)

			document.querySelector('body').classList.add('fixed')

			modal.classList.add('open')
		})
	})

	document.querySelectorAll(closeModalSelectors).forEach((element) => {
		element.addEventListener('click', (event) => {
			if (event.target == event.currentTarget) {
				const modal = event.target.closest('.modal')

				document.querySelector('body').classList.remove('fixed')

				modal.classList.remove('open')
			}
		})
	})
})
