import axios from 'axios';
const apiURL = window.atena_apiURL;

/* eslint-disable-next-line */
export const api = axios.create({
  baseURL: `${apiURL}`,
});

/**
 * Parse taxonomies from WP Rest API response
 *
 * @param Array taxonomies Taxonomies
 */
export const parseFilters = (filter) => {
  return Object.values(filter).map(filter => {
    const {term_id: id, count, slug, name, taxonomy} = filter;
    return {id, count, slug, name, taxonomy, selected: false};
  });
};