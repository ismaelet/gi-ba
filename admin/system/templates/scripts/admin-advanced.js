$(document).ready(function () {
	// sort images
	$('.item-imgs').sortable()

	// change color of inventory status
	$('.inventory-field .sel').on('change', function () {
		const inventory = $('.inventory-field')

		if ($(this).val() == 1) {
			inventory.removeClass('inventory-field_absent').addClass('inventory-field_present')
		} else {
			inventory.removeClass('inventory-field_present').addClass('inventory-field_absent')
		}
	})

	// slug
	let autoSlug = true

	$('.slug-inp, input[name="name"]').keyup(async function () {
		const self = $(this)
		const inpName = $(this).attr('name')

		if (inpName == 'slug' || autoSlug) {
			const name = self.val()
			const parameters = { name: name }
			const response = await AJAX('item-slug', parameters)

			if (inpName == 'slug') {
				autoSlug = false
			}

			$('.slug-inp').val(response['slug'])
		}
	})

	// add a new image
	$('.js-img-trigger').click(function () {
		const imgBox = $(this).parents('.item-img')

		imgBox.find('.img-file').click()
	})

	$('.img-file').on('change', function () {
		const imgBox = $(this).parents('.item-img')

		imgBox.find('.upload-img').click()
	})

	// delete an image
	$('.item-img-delete').click(function () {
		const imgBox = $(this).parents('.item-img')

		imgBox.find('.delete-img').click()
	})

	// SEO
	$('input[name="name"], input[name="title"]').keyup(function () {
		let title = $(this).val().trim()

		if (!title) {
			title = 'Título do produto'
		}

		$('.seo-title').text(title)
	})

	$('textarea[name="info"], textarea[name="description"]').keyup(function () {
		let info = $(this).val().trim()
		$('.seo-text').text(info)
	})

	// FAQs
	$('.faq-add').click(function () {
		const faqs = $('.faqs')
		const index = faqs.data('count') + 1

		faqs.data('count', index)

		faqs.find('.form-field:nth-child(' + index + ')').removeClass('hidden')

		if (index >= 10) $(this).hide()
	})

	// quiz
	$('.quiz-answer').click(function () {
		const self = $(this)
		let status = self.data('status')

		status++

		if (status > 2) status = 0

		self.data('status', status)
			.removeClass('status-0')
			.removeClass('status-1')
			.removeClass('status-2')
			.addClass('status-' + status)
		self.find('input').val(status)
	})

	// variations
	attachDeleteVariationEvent()
	attachAddVariantEvent()
	attachDeleteVariantEvent()

	if (document.querySelector('.add-variation-btn')) {
		document.querySelector('.add-variation-btn').addEventListener('click', async function () {
			const variationsPanel = document.querySelector('.variations')
			const name = document.querySelector('.add-variation-inp').value
			const parameters = { name: name }
			const html = await AJAX('item-add-variation', parameters, 'text')

			let variationCount = parseInt(variationsPanel.getAttribute('data-count'))

			if (name) {
				document.querySelector('.variation-list').innerHTML += html
				variationsPanel.setAttribute('data-count', variationCount++)

				attachAddVariantEvent()
			}

			if (variationCount > 0) {
				document.querySelector('.add-variation').style.display = 'none'
			}
		})
	}

	function attachDeleteVariationEvent() {
		if (document.querySelector('.delete-variation')) {
			document.querySelector('.delete-variation').addEventListener('click', (e) => {
				e.target.closest('.variation').outerHTML = ''
			})
		}
	}

	function attachAddVariantEvent() {
		$('.add-variant').click(async function () {
			const html = await AJAX('item-add-variant', {}, 'text')
			$('.variants').append(html)

			attachDeleteVariantEvent()
		})
	}

	function attachDeleteVariantEvent() {
		$('.delete-variant').click(function () {
			$(this).parents('.variant').remove()
		})
	}

	// form submit
	const form = document.getElementById('form')

	form.addEventListener(
		'submit',
		function () {
			$('.loading').fadeIn()
		},
		false
	)
})
