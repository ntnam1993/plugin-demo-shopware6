import Plugin from 'src/plugin-system/plugin.class'
import DomAccess from 'src/helper/dom-access.helper'
import ViewportDetection from 'src/helper/viewport-detection.helper'

export default class StickyHeader extends Plugin {
    init() {
        this.PluginManager = window.PluginManager
        // let mainNav =  document.getElementById('mainNavigation')
        // console.log('main Nav: ', mainNav,  this.PluginManager.getPluginInstanceFromElement(mainNav))

        this.createElement()
        this.addEventListeners()
        this.reinitializePlugin()
        // this.subscriberEvent()
    }

    createElement() {
        console.log("plugin sticky header create element")
        this._navClone = this.el.cloneNode(true)
        this._navClone.classList.add('js-header-main-sticky')
        this._navClone.removeAttribute('id')
        document.body.appendChild(this._navClone)
        console.log("after clone")
    }

    addEventListeners() {
        document.removeEventListener('scroll', this.onScroll.bind(this))
        document.addEventListener('scroll', this.onScroll.bind(this))

    }

    onScroll() {
        const scrollPosition = document.documentElement.scrollTop
        if (scrollPosition > 100 && !this._navClone.classList.contains('is--active')) {
            this._navClone.classList.add('is--active')
        }

        if (scrollPosition < 100 && this._navClone.classList.contains('is--active')) {
            this._navClone.classList.remove('is--active')
        }
    }

    reinitializePlugin() {
        this.PluginManager.initializePlugin(
            'FlyoutMenu',
            '[data-flyout-menu="true"'
        )
    }

    subscriberEvent() {
        document.$emitter.subscribe('Viewport/hasChanged', this.update(), {scope: this})
    }
    update() {
        if (this.pluginSHouldActive()) {
            // check if plugin is initialized

            this.reinitializePlugin()
        } else {
            //
            this.destroy()
        }

    }
}
