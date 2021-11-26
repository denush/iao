function fetchData(path, form) {

	return fetch(path, {
		credentials: 'include',
		method: 'post',
		body: form
	})
	.then(res => res.json())
	.catch(err => {
		throw err;
	});
}

export default fetchData;