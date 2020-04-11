import React from 'react';
import PropTypes from 'prop-types';

export default function Card({ className, children }) {
  return (
    <div
      className={`rounded overflow-hidden shadow-lg mt-2${null !== className ? ` ${className}` : ''}`}
    >
      <div className="px-4 py-2">
        {children}
      </div>
    </div>
  );
}

Card.propTypes = {
  className: PropTypes.string,
  children: PropTypes.node,
};

Card.defaultProps = {
  className: null,
  children: null,
};
