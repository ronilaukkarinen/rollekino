import axios from 'axios';

const apiURL = window.rollekino_apiURL;

/* eslint-disable-next-line */
export const api = axios.create({
  baseURL: `${apiURL}`,
});

/**
 * Parse taxonomies from WP Rest API response
 *
 * @param Array taxonomies Taxonomies
 */
export const parseFilters = (filter) => Object.values(filter).map((term) => {
  const {
    term_id: id, count, slug, name, taxonomy,
  } = term;
  return {
    id, count, slug, name, taxonomy, selected: false,
  };
});
