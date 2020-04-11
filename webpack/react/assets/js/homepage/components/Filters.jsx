import React from 'react';
import PropTypes from 'prop-types';

export default function Filters(props) {
  const { nameFilter, setNameFilter } = props;

  function handleNameFilterChange(e) {
    setNameFilter(e.target.value);
  }

  return (
    <div>
      <label htmlFor="nameFilter">Rechercher un joueur :</label>
      {''}
      <input id="nameFilter" type="text" className="border border-gray-400 rounded py-1 px-2 m-2 focus:shadow-inner" value={nameFilter} onChange={handleNameFilterChange} />
    </div>
  );
}

Filters.propTypes = {
  nameFilter: PropTypes.string.isRequired,
  setNameFilter: PropTypes.func.isRequired,
};
