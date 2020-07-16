// USe jQuery to objectify webpage
import $ from 'jquery';

// Create class to build live search function
class Search
{
    // Describe and create/initial search object
    constructor()
    {
        this.addSearchHTML();
        this.resultsDiv = $("#search-overlay__results");
        this.searchButton = $(".js-search-trigger");
        this.closeSearch = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-term");
        this.events();

        // State variables
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;

        // Persistence variables to remember user input
        this.previousValue;
        this.typingTimer;
    }

    // Events to listen for
    events()
    {
        this.searchButton.on("click", this.openOverlay.bind(this));
        this.closeSearch.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));      // Search for keypress on webpage
        this.searchField.on("keyup", this.typingLogic.bind(this));          // Respond to search field input
    }

    // Search methods
    openOverlay()
    {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        this.searchField.val('');                                           // Clear search field each time it is opened
        setTimeout(() => this.searchField.focus(), 301);                    // Wait till overlay is loaded before focusing on search field
        this.isOverlayOpen = true;
        return false;                                                       // Prevents non-JavaScript search page loading if search icon is clicked
    }

    closeOverlay()
    {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.isOverlayOpen = false;
    }

    /* Respond to keypress of 's' to open search overlay and 'esc' to close overlay,
    and prevent focus being taken away from other text fields */
    keyPressDispatcher(event)
    {
        if (event.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus'))
        {
            this.openOverlay();
        }

        if (event.keyCode == 27 && this.isOverlayOpen)
        {
            this.closeOverlay();
        }
    }

    // Respond to input to search field
    typingLogic()
    {
        // Make sure method is called only on input in text field
        if (this.searchField.val() != this.previousValue)
        {

            /* Allow user to finish typing search term before making server request
            or wait given amount of microseconds in timeout function (Set to 500 initially) */
            clearTimeout(this.typingTimer);

            // Only show loader if there is input in search field
            if (this.searchField.val())
            {
                if (!this.isSpinnerVisible)
                {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 500);
            }
            else
            {
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }
        }
        this.previousValue = this.searchField.val();
    }

    // Method to display results of search in search overlay
    getResults()
    {
        // Make JSON query using custon REST API
        $.getJSON(websiteData.root_url + '/wp-json/IFRSwebsite/v1/search?term=' + this.searchField.val(), (results) => {
            // Output 3 column results view arranged by result type
            this.resultsDiv.html(`
                <div class="row">
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">General Information</h2>
                        ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No results</p>'}
                            ${results.generalInfo.map(result => `<li><a href="${result.permalink}">${result.title}</a> ${result.post_type == 'post' ? `by ${result.authorName}` : ''}</li>`).join('')}
                        ${results.generalInfo.length ? '</ul>': ''}
                    </div>
                    
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Members</h2>
                        ${results.members.length ? '<ul class="professor-cards">' : `<p>No results - <a href="${websiteData.root_url}/members">View all members</a></p>`}
                            ${results.members.map(result => `
                                <li class="professor-card__list-item">
                                    <a class="professor-card" href="${result.permalink}">
                                        <img class="professor-card__image" src="${result.image}">
                                        <span class="professor-card__name">${result.title}</span>
                                    </a>
                                </li>
                            `).join('')}
                        ${results.members.length ? '</ul>': ''}
                        <h2 class="search-overlay__section-title">Programs</h2>
                        ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No results - <a href="${websiteData.root_url}/programs">View all training programs currently running</a></p>`}
                            ${results.programs.map(result => `<li><a href="${result.permalink}">${result.title}</a></li>`).join('')}
                        ${results.programs.length ? '</ul>': ''}
                    </div>

                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Events</h2>
                        ${results.events.length ? '<ul class="link-list min-list">' : `<p>No results - <a href="${websiteData.root_url}/events">View all upcoming events</a></p>`}
                            ${results.events.map(result => `
                                <div class="event-summary">
                                    <a class="event-summary__date t-center" href="${result.permalink}">
                                        <span class="event-summary__month">${result.month}</span>
                                        <span class="event-summary__day">${result.day}</span>  
                                    </a>
                                    <div class="event-summary__content">
                                        <h5 class="event-summary__title headline headline--tiny"><a href="${result.permalink}">${result.title}</a></h5>
                                        <p>${result.description}<a href="${result.permalink}" class="nu c-white"><br>Learn more</a>
                                        </p>
                                    </div>
                                </div>
                            `).join('')}
                        <h2 class="search-overlay__section-title">Publications</h2>
                        ${results.publications.length ? '<ul class="link-list min-list">' : `<p>No results - <a href="${websiteData.root_url}/publications">View all publications</a></p>`}
                            ${results.publications.map(result => `<li><a href="${result.permalink}">${result.title}</a> by ${result.authorName}</li>`).join('')}
                        ${results.publications.length ? '</ul>': ''}
                    </div>
                </div>
            `);
            this.isSpinnerVisible = false;
        });
    }

    // Method to add search overlay to site if user's JavaScript is not disabled
    addSearchHTML()
    {
        $("body").append(`
            <div class="search-overlay">
                <div class="search-overlay__top">
                    <div class="container">
                        <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                        <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
                        <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="container">
                    <div id="search-overlay__results">
                        
                    </div>
                </div>
            </div>
        `);
    }
}

// Export to be used as a module in scripts.js
export default Search;