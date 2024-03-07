let loadClass = 'js-load'

document.addEventListener('DOMContentLoaded', () => {
	let loadableElements = document.querySelectorAll('.js-load:not([data-load-type="always"])')

	const defaultOptions = {
		root: null,
		threshold: 0.5,
		rootMargin: '0px',
	}

	const elementStartOptions = {
		root: null,
		threshold: 0,
		rootMargin: '0px',
	}

	const observer = new IntersectionObserver(observerCallback, defaultOptions)
	const observerStart = new IntersectionObserver(observerCallback, elementStartOptions)

	function observerCallback(entries) {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				entry.target.classList.remove(loadClass)
			}
		})
	}

	loadableElements.forEach((element) => {
		if (element) {
			if (element.getAttribute('data-load-threshold') == 0) {
				observerStart.observe(element)
			} else {
				observer.observe(element)
			}
		}
	})
})

window.addEventListener('load', () => {
	let loadableElements = document.querySelectorAll('.js-load[data-load-type="always"]')

	loadableElements.forEach((element) => {
		if (element) {
			element.classList.remove(loadClass)
		}
	})
})
