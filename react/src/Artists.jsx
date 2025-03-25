import './Artists.css'; // Add this line to import the CSS file
import { useEffect, useState } from 'react';

function Artists({ navigateTo }) {
  const API_BASE_URL = 'http://127.0.0.1:44444/api';
  const [artists, setArtists] = useState([]);
  const [filter, setFilter] = useState('');
  const [sortOrder, setSortOrder] = useState('asc');

  useEffect(() => {
    fetch(`${API_BASE_URL}/artists`)
      .then((response) => response.json())
      .then((data) => setArtists(data));
  }, []);

  const filteredArtists = artists
    .filter((artist) =>
      artist.name.toLowerCase().includes(filter.toLowerCase())
    )
    .sort((a, b) =>
      sortOrder === 'asc'
        ? a.name.localeCompare(b.name)
        : b.name.localeCompare(a.name)
    );

  return (
    <div className="artists-container">
      <h1>Liste des artistes</h1>
      <input
        type="text"
        placeholder="Filtrer par nom"
        value={filter}
        onChange={(e) => setFilter(e.target.value)}
        className="filter-input"
      />
      <button
        onClick={() => setSortOrder(sortOrder === 'asc' ? 'desc' : 'asc')}
        className="sort-button"
      >
        Trier {sortOrder === 'asc' ? '↓' : '↑'}
      </button>
      <ul className="artists-list">
        {filteredArtists.map((artist, index) => (
          <li
            key={artist.id}
            onClick={() => navigateTo('artist', artist.id)}
            className={`artist-item ${
              index === filteredArtists.length - 1 ? 'last-item' : ''
            }`}
          >
            {artist.name}
          </li>
        ))}
      </ul>
    </div>
  );
}

export default Artists;
