import './Events.css'; // Add this line to import the CSS file
import { useEffect, useState } from 'react';

function Events({ navigateTo }) {
  const API_BASE_URL = 'http://127.0.0.1:44444/api';
  const [events, setEvents] = useState([]);
  const [filter, setFilter] = useState('');
  const [sortBy, setSortBy] = useState('name');
  const [sortOrderName, setSortOrderName] = useState('asc');
  const [sortOrderDate, setSortOrderDate] = useState('asc');

  useEffect(() => {
    fetch(`${API_BASE_URL}/events`)
      .then((response) => response.json())
      .then((data) => setEvents(data));
  }, []);

  const filteredEvents = events
    .filter((event) => event.name.toLowerCase().includes(filter.toLowerCase()))
    .sort((a, b) => {
      if (sortBy === 'name') {
        return sortOrderName === 'asc'
          ? a.name.localeCompare(b.name)
          : b.name.localeCompare(a.name);
      } else {
        return sortOrderDate === 'asc'
          ? new Date(a.date) - new Date(b.date)
          : new Date(b.date) - new Date(a.date);
      }
    });

  return (
    <div className="events-container">
      <h1>Liste des événements</h1>
      <input
        type="text"
        placeholder="Filtrer par nom"
        value={filter}
        onChange={(e) => setFilter(e.target.value)}
        className="filter-input"
      />
      <button
        onClick={() => {
          setSortBy('name');
          setSortOrderName(sortOrderName === 'asc' ? 'desc' : 'asc');
        }}
        className="sort-button"
      >
        Trier par nom {sortOrderName === 'asc' ? '↓' : '↑'}
      </button>
      <button
        onClick={() => {
          setSortBy('date');
          setSortOrderDate(sortOrderDate === 'asc' ? 'desc' : 'asc');
        }}
        className="sort-button"
      >
        Trier par date {sortOrderDate === 'asc' ? '↓' : '↑'}
      </button>
      <ul className="events-list">
        {filteredEvents.map((event, index) => (
          <li
            key={event.id}
            onClick={() => navigateTo('event', event.id)}
            className={`event-item ${
              index === filteredEvents.length - 1 ? 'last-item' : ''
            }`}
          >
            {event.name} - {new Date(event.date).toLocaleDateString()}
          </li>
        ))}
      </ul>
    </div>
  );
}

export default Events;
