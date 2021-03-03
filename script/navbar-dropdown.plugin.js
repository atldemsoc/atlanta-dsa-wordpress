import Bulma from '@vizuaalog/bulmajs/src/core';
import Plugin from '@vizuaalog/bulmajs/src/plugin';

/**
 * @module NavbarDropdown
 */
class NavbarDropdown extends Plugin {
	/**
	 * Handle parsing the DOMs data attribute API.
	 * @param {HtmlElement} element The root element for this instance
	 * @return {undefined}
	 */
	static parse(element) {
		new NavbarDropdown({
			element: element
		});
	}

	/**
	 * Returns a string containing the element class this plugin supports.
	 * @returns {string} The class name.
	 * @throws {Error} Thrown if this method has not been replaced.
	 */
	static getRootClass() {
		return 'has-dropdown';
	}

	/**
	 * Plugin constructor
	 * @param  {Object} options The options object for this plugin
	 * @return {this} The newly created instance
	 */
	constructor(options) {
		super(options);

		// Work out the parent if it hasn't been supplied as an option.
		if(this.parent === null) {
			this.parent = this.option('element').parentNode;
		}

		/**
		 * The root dropdown element.
		 * @type {HTMLElement}
		 */
		this.element = this.option('element');
		this.element.setAttribute('data-bulma-attached', 'attached');

		/**
		 * The element to trigger when clicked.
		 * @type {HTMLElement}
		 */
		this.trigger = this.element.querySelector('.navbar-link');

		this.registerEvents();
	}

	/**
	 * Register all the events this module needs.
	 * @return {undefined}
	 */
	registerEvents() {
		this.trigger.addEventListener('click', this.handleTriggerClick.bind(this));
	}

	/**
	 * Handle the click event on the trigger.
	 * @return {undefined}
	 */
	handleTriggerClick() {
		if(this.element.classList.contains('is-active')) {
			this.element.classList.remove('is-active');
		} else {
			this.element.classList.add('is-active');
		}
	}
}

Bulma.registerPlugin('navbar-dropdown', NavbarDropdown);

export default NavbarDropdown;

