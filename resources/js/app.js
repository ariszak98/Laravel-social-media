import './bootstrap';
import Search from './live-search'
import Profile from './profile';

if ( document.querySelector(".profile-nav") ) {
    new Profile();
}

if ( document.querySelector(".header-search-icon") ) {
    new Search();
}