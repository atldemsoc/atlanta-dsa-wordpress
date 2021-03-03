import bulmajs from '@vizuaalog/bulmajs';
import NavbarDropdown from './navbar-dropdown.plugin';

window.addEventListener('DOMContentLoaded', (event) => {
	let iframes = document.querySelectorAll('iframe');

	for (const iframe of iframes) {
		let height = iframe.getAttribute('height');

		if (height) {
			iframe.setAttribute('style', `height:${height}px; ${iframe.getAttribute('style')}`);
		}
	}

	let searchTriggers = document.querySelectorAll('.site__search-trigger');

	for (const searchTrigger of searchTriggers) {
		searchTrigger.addEventListener('click', () => {
			let searchBox = document.querySelector('.site__search-box');
			let searchInput = document.querySelector('.site__search-input');

			if (searchBox) {
				const active = 'is-inactive';
				const classStr = searchBox.getAttribute('class');

				if (classStr.includes(active)) {
					searchBox.setAttribute('class', classStr.replace(active, '').trim());

					if (searchInput) {
						searchInput.focus();
					}
				} else {
					searchBox.setAttribute('class', `${classStr} ${active}`);
				}
			}
		});
	}
});
