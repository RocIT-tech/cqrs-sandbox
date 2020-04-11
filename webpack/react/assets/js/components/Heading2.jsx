import React from 'react';
import PropTypes from 'prop-types';

export default function Heading2({ children }) {
  return (
    <h2 className="text-2xl font-bold mb-2">
      {children}
    </h2>
  );
}

Heading2.propTypes = {
  children: PropTypes.node,
};

Heading2.defaultProps = {
  children: null,
};
