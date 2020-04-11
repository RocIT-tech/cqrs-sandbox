import React, { useEffect, useState } from 'react';
import TopPersons from './TopPersons';
import Heading2 from '../../components/Heading2';
import Filters from './Filters';

/**
 * @param {Array} topPersons
 * @param {String} nameFilter
 * @returns {*}
 */
function getFilteredTopPersons(topPersons, nameFilter) {
  const nameFilterLowercase = nameFilter.toLowerCase();

  return topPersons.filter((topPerson) => (
    topPerson.personName.toLowerCase().search(nameFilterLowercase) !== -1
  ));
}

export default function HomepageApp() {
  const [topPersons, setTopPersons] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const [nameFilter, setNameFilter] = useState('');

  useEffect(() => {
    setIsLoading(true);

    fetch('/api/persons/top')
      .then((response) => {
        response.json()
          .then((responseJson) => {
            setTopPersons(responseJson);
          });
        setIsLoading(false);
      });
  }, []);

  return (
    <div className="container mx-auto">
      <Heading2>Classement des meilleurs joueurs :</Heading2>
      <Filters nameFilter={nameFilter} setNameFilter={setNameFilter} />
      <TopPersons
        topPersons={getFilteredTopPersons(topPersons, nameFilter)}
        isLoading={isLoading}
      />
    </div>
  );
}
