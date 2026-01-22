// cart
const cartStatusSelector = '.js-cart-status'

// discounts
const cartDownPaymentSelector = '.js-down-payment'
const cartDownPaymentWithVatSelector = '.js-down-payment-with-vat'
const cartFixedDiscountSelector = '.js-fixed-discount'
const cartDiscountWithVatSelector = '.js-discount-with-vat'
const cartPercentageDiscountSelector = '.js-percentage-discount'

// recap
const recapNetPriceSelector = '.js-recap-net'
const recapVatSelector = '.js-recap-vat'
const recapDownPaymentSelector = '.js-recap-down-payment'
const recapDiscountSelector = '.js-recap-discount'
const recapPriceSelector = '.js-recap-total'

// customer
const customerIdSelector = '[name="customerId"]'
const customerPhoneSelector = '[name="customerPhone"]'
const customerInfoSelector = '[name="customerInfo"]'

// products
const productIdSelector = '[name="productId[]"]'
const productNameSelector = '[name="productName[]"]'

// orders
const addOrderSelector = '.js-add-order'
const deleteOrderSelector = '.js-delete-order'
const orderSelector = '.js-order'
const orderInfoSelector = '.js-order-info'
const orderStatusSelector = '.js-order-status'
const orderQuantitySelector = '.js-order-quantity'
const orderVatSelector = '.js-order-vat'
const orderRealProductPriceSelector = '.js-order-real-price'
const orderNetProductPriceSelector = '.js-order-net-price'
const orderGrossProductPriceSelector = '.js-order-gross-price'
const orderPriceSelector = '.js-order-total-price'

// after document load
document.addEventListener('DOMContentLoaded', function () {
	/*
	 * Setup
	 */
	// cart
	const cartStatusInput = document.querySelector(cartStatusSelector)

	// discounts
	const cartDownPayment = document.querySelector(cartDownPaymentSelector)
	const cartDownPaymentWithVat = document.querySelector(cartDownPaymentWithVatSelector)
	const cartFixedDiscount = document.querySelector(cartFixedDiscountSelector)
	const cartDiscountWithVat = document.querySelector(cartDiscountWithVatSelector)
	const cartPercentageDiscount = document.querySelector(cartPercentageDiscountSelector)

	// recap
	const recapNetPriceElement = document.querySelector(recapNetPriceSelector)
	const recapVatElement = document.querySelector(recapVatSelector)
	const recapDownPayment = document.querySelector(recapDownPaymentSelector)
	const recapDiscount = document.querySelector(recapDiscountSelector)
	const recapPriceElement = document.querySelector(recapPriceSelector)

	// customer
	const customerIdInput = document.querySelector(customerIdSelector)
	const customerPhoneInput = document.querySelector(customerPhoneSelector)
	const customerInfoInput = document.querySelector(customerInfoSelector)

	// orders
	const addOrderElement = document.querySelector(addOrderSelector)

	/*
	 * Events
	 */
	// auto fill customer data
	customerIdInput.addEventListener('change', async () => {
		const customerId = customerIdInput.value
		const customerData = await get_class_properties('Ecommerce\\Customer', ['phone', 'info'], customerId)

		customerPhoneInput.value = customerData.phone
		customerInfoInput.value = customerData.info
	})

	// auto fill price
	document.querySelectorAll(productIdSelector).forEach((element) => {
		element.addEventListener('change', async () => {
			const order = element.closest(orderSelector)
			const productId = element.value
			const productData = await get_class_properties('Ecommerce\\Product', 'formattedPrice', productId)

			update_order_prices(order, productData.formattedPrice)
		})
	})

	// update price on net price change
	document.querySelectorAll([orderNetProductPriceSelector, orderGrossProductPriceSelector, orderPriceSelector].join(',')).forEach((element) => {
		element.addEventListener('keyup', async () => {
			update_price(element)
		})
		element.addEventListener('input', async () => {
			update_price(element)
		})
		element.addEventListener('change', async () => {
			update_price(element)
		})

		function update_price(element) {
			const order = element.closest(orderSelector)
			const orderQuantity = order.querySelector(orderQuantitySelector).value
			const orderHasVat = order.querySelector(orderVatSelector).checked
			const orderRealProductPriceInput = order.querySelector(orderRealProductPriceSelector)
			const price = element.value

			let netPrice

			if (element.classList.contains(orderNetProductPriceSelector.split('.').join(''))) {
				netPrice = price ? parseFloat(price) : 0
			} else if (element.classList.contains(orderGrossProductPriceSelector.split('.').join(''))) {
				netPrice = orderHasVat ? price / 1.22 : price
			} else if (element.classList.contains(orderPriceSelector.split('.').join(''))) {
				netPrice = orderHasVat ? price / (1.22 * orderQuantity) : price / orderQuantity
			}

			if (isFinite(netPrice)) {
				orderRealProductPriceInput.value = netPrice

				update_order_prices(order)
			}
		}
	})

	// update the recap on discount and down payment changes
	document
		.querySelectorAll([cartDownPaymentSelector, cartFixedDiscountSelector, cartPercentageDiscountSelector, cartDownPaymentWithVatSelector, cartDiscountWithVatSelector].join(','))
		.forEach((element) => {
			element.addEventListener('keyup', async () => {
				update_orders_recap(element)
			})
			element.addEventListener('input', async () => {
				update_orders_recap(element)
			})
			element.addEventListener('change', async () => {
				update_orders_recap(element)
			})
		})

	// edit quantity
	document.querySelectorAll([orderQuantitySelector, orderVatSelector].join(',')).forEach((element) => {
		const order = element.closest(orderSelector)

		element.addEventListener('keyup', async () => {
			update_order_prices(order)
		})
		element.addEventListener('change', async () => {
			update_order_prices(order)
		})
		element.addEventListener('input', async () => {
			update_order_prices(order)
		})
	})

	// add an order row
	addOrderElement.addEventListener('click', () => {
		const hiddenOrderSelector = orderSelector + ':not(.active):not(.deleted)'
		const hiddenOrderCount = document.querySelectorAll(hiddenOrderSelector).length
		const newOrder = document.querySelector(hiddenOrderSelector)

		newOrder.classList.add('active')
		newOrder.classList.remove('hidden')
		newOrder.querySelector(orderStatusSelector).dispatchEvent(changeEvent)
		newOrder.querySelector(productNameSelector).focus()

		update_order_prices(newOrder)

		if (!hiddenOrderCount) {
			addOrderElement.classList.remove('active')
		}
	})

	// select order info on ENTER in product
	document.querySelectorAll(ajaxSearchSelector).forEach((element) => {
		element.addEventListener('keydown', (event) => {
			if (event.key == 'Enter') {
				event.preventDefault()
				element.nextElementSibling.focus()
			}
		})
	})

	// add a new row on ENTER in any input
	document.querySelectorAll(orderSelector + ' input').forEach((element) => {
		element.addEventListener('keydown', (event) => {
			if (event.key === 'Enter') {
				event.preventDefault()
				addOrderElement.click()
			}
		})
	})

	// update status color on cart status change
	cartStatusInput.addEventListener('change', () => {
		const cartStatus = cartStatusInput.value

		document.querySelectorAll(orderStatusSelector).forEach((element) => {
			element.value = cartStatus
		})

		update_status_color()
	})

	// update status color on order status change
	document.querySelectorAll(orderStatusSelector).forEach((element) => {
		element.addEventListener('change', () => {
			let minOrderStatus = false

			document.querySelectorAll(orderSelector + '.active ' + orderStatusSelector).forEach((element) => {
				const orderStatus = parseInt(element.value)

				if (!minOrderStatus) {
					minOrderStatus = orderStatus
				} else if (orderStatus < minOrderStatus && orderStatus >= 0) {
					minOrderStatus = orderStatus
				}
			})

			cartStatusInput.value = minOrderStatus

			update_status_color()
		})
	})

	// show long descriptions
	document.querySelectorAll(orderInfoSelector).forEach((element) => {
		element.addEventListener('keyup', () => {
			element.setAttribute('title', element.value)
		})
	})

	// delete an order
	document.querySelectorAll(deleteOrderSelector).forEach((element) => {
		element.addEventListener('click', () => {
			const order = element.closest(orderSelector)

			order.classList.remove('active')
			order.classList.add('deleted')
			order
				.querySelectorAll([orderQuantitySelector, orderRealProductPriceSelector, orderNetProductPriceSelector, orderGrossProductPriceSelector, orderPriceSelector].join(','))
				.forEach((element) => {
					element.value = 0
				})

			order.querySelector(orderStatusSelector).dispatchEvent(changeEvent)

			update_orders_recap()
		})
	})

	/*
	 * Functions
	 */
	// update prices
	function update_order_prices(order, fixedPrice = false) {
		const vatInput = order.querySelector(orderVatSelector)
		const quantityInput = order.querySelector(orderQuantitySelector)
		const realPriceInput = order.querySelector(orderRealProductPriceSelector)
		const netPriceInput = order.querySelector(orderNetProductPriceSelector)
		const grossPriceInput = order.querySelector(orderGrossProductPriceSelector)
		const totalPriceInput = order.querySelector(orderPriceSelector)

		// set blocks
		const realPrice = fixedPrice ? parseFloat(fixedPrice) : parseFloat(realPriceInput.value)

		// fetch data
		const quantity = quantityInput.value ? parseInt(quantityInput.value) : 0
		const hasVat = vatInput.checked

		// calculate prices
		const netPrice = realPrice
		const grossPrice = hasVat ? netPrice * 1.22 : netPrice
		const totalPrice = grossPrice * quantity

		// update form
		realPriceInput.value = netPrice

		quantityInput.setAttribute('title', quantity)
		if (netPriceInput !== document.activeElement) {
			netPriceInput.value = netPrice.toFixed(2)
		}
		netPriceInput.setAttribute('title', netPrice.toFixed(2))

		if (grossPriceInput !== document.activeElement) {
			grossPriceInput.value = grossPrice.toFixed(2)
		}
		grossPriceInput.setAttribute('title', grossPrice.toFixed(2))

		if (totalPriceInput !== document.activeElement) {
			totalPriceInput.value = totalPrice.toFixed(2)
		}
		totalPriceInput.setAttribute('title', totalPrice.toFixed(2))

		update_orders_recap()
	}

	// update recap panel
	function update_orders_recap() {
		let totalNetPrice = 0,
			totalVat = 0,
			totalPrice = 0

		// order data
		document.querySelectorAll(orderSelector).forEach((order) => {
			const hasVat = order.querySelector(orderVatSelector).checked
			const totalPriceInput = order.querySelector(orderPriceSelector)
			const totalGrossPrice = totalPriceInput.value ? parseFloat(totalPriceInput.value) : 0

			totalNetPrice += hasVat ? totalGrossPrice / 1.22 : totalGrossPrice
			totalVat += hasVat ? totalGrossPrice * 0.22 : 0
			totalPrice += totalGrossPrice
		})

		// cart data
		const downPaymentWithVat = cartDownPaymentWithVat.checked
		const discountWithVat = cartDiscountWithVat.checked

		let downPayment = parseFloat(cartDownPayment.value ? cartDownPayment.value : 0)
		let fixedDiscount = parseFloat(cartFixedDiscount.value ? cartFixedDiscount.value : 0)
		let percentageDiscount = parseFloat(cartPercentageDiscount.value ? cartPercentageDiscount.value : 0)

		downPayment = downPaymentWithVat ? downPayment : downPayment * 1.22
		fixedDiscount = discountWithVat ? fixedDiscount : fixedDiscount * 1.22

		const totalDiscount = totalPrice * (percentageDiscount / 100) + fixedDiscount

		totalPrice -= downPayment
		totalPrice -= totalDiscount

		// update recap
		recapNetPriceElement.innerHTML = totalNetPrice.toFixed(2)
		recapVatElement.innerHTML = totalVat.toFixed(2)
		recapDownPayment.innerHTML = downPayment.toFixed(2)
		recapDiscount.innerHTML = totalDiscount.toFixed(2)
		recapPriceElement.innerHTML = totalPrice.toFixed(2)
	}

	function update_status_color() {
		update(cartStatusInput)

		document.querySelectorAll(orderStatusSelector).forEach((element) => {
			update(element)
		})

		function update(element) {
			element.dataset.statusId = element.value
		}
	}
})
