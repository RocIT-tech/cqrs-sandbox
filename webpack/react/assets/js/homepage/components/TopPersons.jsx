import React from 'react';
import PropTypes from 'prop-types';
import TopPerson from './TopPerson';

export default function TopPersons(props) {
  const { topPersons, isLoading } = props;

  return (
    <div>
      {true === isLoading ? (
        <div className="text-center py-2">Chargement...</div>
      ) : (
        <div className="flex flex-row flex-wrap -mx-2">
          {topPersons.map((topPerson) => (
            <div key={topPerson.personId} className="w-1/5 p-2">
              <TopPerson topPerson={topPerson} />
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

TopPersons.propTypes = {
  topPersons: PropTypes.arrayOf(PropTypes.shape({
    personId: PropTypes.string.isRequired,
  })).isRequired,
  isLoading: PropTypes.bool.isRequired,
};
