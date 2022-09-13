function mClick() {
	let contacts = document.querySelectorAll('[data-eod]');

	if (contacts.length > 0) {
		for (const item of contacts) {
			if (item.dataset.eod === '' || item.dataset.eoa === '') continue; // skip this one

			const isLink = (item.dataset.eol === 'true') ? true : false;
			const recipient = item.dataset.eoa;
			const contact = recipient + "@" + item.dataset.eod.replaceAll('|', '.');
			const aria = recipient + " at " + item.dataset.eod.replaceAll('|', ' dot ');

			let mailto = null;
			if (isLink) {
				mailto = document.createElement('a');
				mailto.href = "#";
				if (item.className !== '') {
					mailto.setAttribute('class', item.className);
				}
				mailto.setAttribute('aria-label', 'Open email program to write a message to: ' + aria);
				mailto.innerHTML = contact.replaceAll('@', '<i hidden>[</i>@<i hidden>]</i>');

				mailto.onclick = (evt) => {
					evt.preventDefault();
					document.location = encodeURI('mailto:'+ contact + '?subject=Log Cabin Quilters Enquiry');
				}
			} else {
				mailto = document.createElement('span');
				if (item.className !== '') {
					mailto.setAttribute('class', item.className);
				}
				mailto.setAttribute('aria-label', aria);
				mailto.innerHTML = contact.replaceAll('@', '<i hidden>[</i>@<i hidden>]</i>');	
			}

			// Replace SPAN with AHREF
			item.parentElement.replaceChild(mailto, item);
		}
	}
}

window.addEventListener('DOMContentLoaded', () => {
	mClick();
});