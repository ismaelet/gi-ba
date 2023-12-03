/*
 * Ajax call
 */
async function AJAX(parcialEndpoint, parameters = {}, contentType = 'json', debug = false) {
	// generate endpoint
	const root = document.querySelector('html').getAttribute('data-client-root')

	let responseData
	let endpoint = parcialEndpoint[0] == '/' ? root + parcialEndpoint : root + '/ajax/' + parcialEndpoint

	if (parameters) {
		endpoint += '?' + new URLSearchParams(parameters)
	}

	// send the request
	const response = await fetch(endpoint)

	// debugger
	if (debug) {
		const debugResponse = response.clone()
		console.log(await debugResponse.text())
	}

	// parse the response
	responseData = await response.text()

	switch (contentType) {
		case 'json':
			try {
				responseData = JSON.parse(responseData)
			} catch (err) {
				console.log('Error: ' + responseData)
				responseData = null
			}
			break
		case 'text':
			responseData = responseData
			break
		default:
			responseData = null
	}

	// return the response
	return responseData
}

/**
 * Call function
 * @param {string} functionName the full function, use double backslash
 * @param {array|string|number} [arguments] the arguments to pass
 * @param {boolean} [debug] the debug option
 */
async function call_function(functionName, arguments = null, debug = false) {
	let parameters = {}

	if (arguments) {
		parameters.arguments = arguments
	}

	return await AJAX('/api/call-function/' + functionName, parameters, 'json', debug)
}

/**
 * Search table
 * @param {string} tableName the name of the table to search
 * @param {array|string} responseFields the list of requested fields
 * @param {array|string} [searchFields] the list of fields to use for the filter
 * @param {string} [search] the searched string
 * @param {string} [orderField] the field to use to order the results
 * @param {string} [orderField] the field to use to order the results
 * @param {boolean} [debug] the debug option
 */
async function search_table(tableName, responseFields, searchFields = null, search = null, filter = null, orderField = null, orderDirection = null, count = null, debug = false) {
	let parameters = {
		responseFields: responseFields,
	}

	if (searchFields) {
		parameters.searchFields = searchFields
	}

	if (search) {
		parameters.search = search
	}

	if (filter) {
		parameters.filter = filter
	}

	if (orderField) {
		parameters.orderField = orderField
	}

	if (orderDirection) {
		parameters.orderDirection = orderDirection
	}

	if (count) {
		parameters.count = count
	}

	return await AJAX('/api/search-table/' + tableName, parameters, 'json', debug)
}

/**
 * Get class properties
 * @param {string} className the full class name, use double backslash
 * @param {array|string} propertyNames the names of the properties to get
 * @param {number} [objectId] the id of the object, if not set, the function will get static properties
 * @param {object} [setProperties] the properties to set
 * @param {boolean} [debug] the debug option
 */
async function get_class_properties(className, propertyNames, objectId = null, setProperties = null, debug = false) {
	let parameters = {
		scope: propertyNames,
	}

	if (objectId !== null) {
		parameters.objectId = objectId
	}

	if (setProperties) {
		parameters.setProperties = format_set_request(setProperties)
	}

	return await AJAX('/api/get-class-properties/' + className, parameters, 'json', debug)
}

/**
 * Call class method
 * @param {string} className the full class name, use double backslash
 * @param {string} methodName the names of the method to call
 * @param {array|string|number} [arguments] the arguments to pass
 * @param {number} [objectId] the id of the object, if not set, the class will be used statically
 * @param {object} [setProperties] the properties to set
 * @param {boolean} [debug] the debug option
 */
async function call_class_method(className, methodName, arguments = null, objectId = null, setProperties = null, debug = false) {
	let parameters = {
		scope: methodName,
	}

	if (arguments) {
		parameters.arguments = arguments
	}

	if (objectId !== null) {
		parameters.objectId = objectId
	}

	if (setProperties) {
		parameters.setProperties = format_set_request(setProperties)
	}

	return await AJAX('/api/call-class-method/' + className, parameters, 'json', debug)
}

/*
 * Function
 */
function format_set_request(setProperties) {
	let setRequest = []

	if (setProperties) {
		for (const key in setProperties) {
			setRequest.push(key + '=' + setProperties[key])
		}
	}

	if (setRequest.length) {
		setRequest.join(',')
	} else {
		setRequest = null
	}

	return setRequest
}
