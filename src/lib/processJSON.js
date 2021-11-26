const PROCESS_JSON = process.env.VUE_APP_ROOT_API + 'process_json.php';

function processJSON(json_str) {
	const form = new FormData();
	form.append('json_str', json_str);

	return fetch(PROCESS_JSON, {
		credentials: 'include',
		method: 'post',
		body: form
	})
	.then(res => res.json())
	.then(res => {
		if (res.code === '0') {
			return res;
		} else {
			if (res.code === '23505') {
				throw new Error('23505'); // duplicate key value
			}
			throw new Error('PROCESS JSON ERROR - ' + res.code + ' ' + res.name);
		}
	})
	.catch(err => { throw err; });

}

export default processJSON;