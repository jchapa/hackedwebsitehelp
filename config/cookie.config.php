<?php

/**
 * Sets a search domain for cookies. Any cookie without this prefix will be ignored.
 * CHANGING THIS IS DANGEROUS. WE DO NOT CURRENTLY KEEP AN ARCHIVE OF OLD COOKIE PREFIXES. CHANGING THIS WILL
 * CAUSE ALL PREVIOUS COOKIE DATA TO BE IGNORED.
 */
const COOKIE_PREFIX = "hwh-";
/**
 * Strict cookies will unset any cookies that aren't defined in configuration
 */
const STRICT_COOKIES = false;