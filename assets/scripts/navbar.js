function navbar() {
	return {
		screenWidth: 0,
		maxWidth: 640,
		menuOpen: false,
		init() {
			this.screenWidth = document.documentElement.clientWidth
			this.menuOpen = (this.screenWidth >= this.maxWidth)
		},
		resize() {
			let newW = document.documentElement.clientWidth
			if ((newW >= this.maxWidth && this.screenWidth < this.maxWidth) || 
				(newW < this.maxWidth && this.screenWidth >= this.maxWidth)) {
				this.menuOpen = (newW >= this.maxWidth)
			}
			this.screenWidth = newW
		},
		goto(id) {
			const el = document.getElementById(id)
			el.scrollIntoView({behavior: 'smooth'})
			el.focus({preventScroll: true})
		}
	};
}