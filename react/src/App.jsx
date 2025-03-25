import './App.css'; // Add this line to import the CSS file
import { useState } from 'react';
import Home from './Home';
import Artists from './Artists';
import ArtistDetails from './ArtistDetails';
import Events from './Events';
import EventDetails from './EventDetails';

function App() {
  const [page, setPage] = useState('home');
  const [selectedId, setSelectedId] = useState(null);

  const navigateTo = (newPage, id = null) => {
    setPage(newPage);
    setSelectedId(id);
  };

  return (
    <div className="app-container">
      <nav className="app-nav">
        <button onClick={() => navigateTo('home')} className="nav-button">
          Accueil
        </button>
        <button onClick={() => navigateTo('artists')} className="nav-button">
          Artistes
        </button>
        <button onClick={() => navigateTo('events')} className="nav-button">
          Événements
        </button>
      </nav>

      {page === 'home' && <Home navigateTo={navigateTo} />}
      {page === 'artists' && <Artists navigateTo={navigateTo} />}
      {page === 'artist' && (
        <ArtistDetails id={selectedId} navigateTo={navigateTo} />
      )}
      {page === 'events' && <Events navigateTo={navigateTo} />}
      {page === 'event' && (
        <EventDetails id={selectedId} navigateTo={navigateTo} />
      )}
    </div>
  );
}

export default App;
