/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps } from "@wordpress/block-editor";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */

export default function Edit() {
  const [isLoading, setIsLoading] = React.useState(false);
  const [query, setQuery] = React.useState("");
  const [pics, setPics] = React.useState([]);
  const [selectedPic, setSelectedPic] = React.useState(null);

  const searchPhotos = async (e) => {
    e.preventDefault();
    setIsLoading(true);
    fetch(
      `https://api.openverse.engineering/v1/images?format=json&shouldPersistImages=true&q=" + ${query}&licence=BY-NC-SA`
    )
      .then((response) => response.json())
      .then((data) => setPics(data.results))
      .then(() => setIsLoading(false));
  };

  return (
    <div {...useBlockProps()}>
      <form className="form" onSubmit={searchPhotos}>
        <label className="label" htmlFor="query"></label>
        <input
          type="text"
          name="query"
          className="input"
          placeholder={`Try "dog" or "apple"`}
          value={query}
          onChange={(e) => setQuery(e.target.value)}
        />
        <button type="submit" className="button">
          Search
        </button>
      </form>

      <div className="openverse-search-results">
        {isLoading ? "Loading..." : ""}
        {!selectedPic && !isLoading && (
          <>
            {pics.map((pic) => (
              <div className="card" key={pic.id}>
                <img
                  className="openverse-image"
                  alt={`${pic.title} by ${pic.provider} - ${pic.license}`}
                  src={pic.thumbnail}
                  width="50%"
                  height="50%"
                  onClick={() => {
                    setSelectedPic(pic);
                  }}
                ></img>
              </div>
            ))}{" "}
          </>
        )}
      </div>
      {selectedPic && (
        <img
          className="openverse-image"
          alt={`${selectedPic.title} by ${selectedPic.provider} - ${selectedPic.license}`}
          src={selectedPic.url}
          width="50%"
          height="50%"
          onClick={() => {
            setSelectedPic(null);
          }}
        ></img>
      )}
    </div>
  );
}

//I am not familiar with the API so I am keeping this here for now.
/**
category: null
creator: "Unknown author"
detail_url: "http://api.openverse.engineering/v1/images/0aff3595-8168-440b-83ff-7a80b65dea42/?format=json"
foreign_landing_url: "https://commons.wikimedia.org/w/index.php?curid=721264"
id: "0aff3595-8168-440b-83ff-7a80b65dea42"
license: "cc0"
license_url: "https://creativecommons.org/publicdomain/zero/1.0/deed.en"
license_version: "1.0"
provider: "wikimedia"
related_url: "http://api.openverse.engineering/v1/images/0aff3595-8168-440b-83ff-7a80b65dea42/related/?format=json"
source: "wikimedia"
thumbnail: "http://api.openverse.engineering/v1/images/0aff3595-8168-440b-83ff-7a80b65dea42/thumb/?format=json"
title: "File:Open book 01.svg"
url: "https://upload.wikimedia.org/wikipedia/co
 */
