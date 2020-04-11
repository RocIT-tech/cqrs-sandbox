import React from 'react';
import PropTypes from 'prop-types';
import Card from '../../components/Card';

export default function TopPerson(props) {
  const { topPerson } = props;

  return (
    <Card title={topPerson.personName} className={topPerson.isWinner ? 'bg-green-200' : ''}>
      <div className="font-bold text-xl mb-2">
        {topPerson.personName}
        {topPerson.isWinner && <i className="fas fa-crown float-right" />}
      </div>
      <div>
        <strong>Rang moyen :</strong>
        {' '}
        #
        {(null !== topPerson.averageRank) ? topPerson.averageRank : '?'}
      </div>
      <div className="mt-2">
        <strong>Nombre de parties :</strong>
        {' '}
        {topPerson.gameCount}
      </div>
      <div className="mt-2">
        <strong>Nombre de victoires :</strong>
        {' '}
        {topPerson.victoryCount}
      </div>
    </Card>
  );
}

TopPerson.propTypes = {
  topPerson: PropTypes.shape({
    personName: PropTypes.string.isRequired,
    gameCount: PropTypes.number.isRequired,
    averageRank: PropTypes.oneOfType([
      PropTypes.number,
      PropTypes.oneOf([null]),
    ]),
    victoryCount: PropTypes.number.isRequired,
    isWinner: PropTypes.bool,
  }).isRequired,
};
