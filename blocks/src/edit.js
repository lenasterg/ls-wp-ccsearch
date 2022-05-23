/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

import {
  Placeholder,
  Button,
  TextControl,
  SelectControl,
  CheckboxControl,
  __experimentalGrid as Grid,
} from "@wordpress/components";

import { useState } from "@wordpress/element";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import {
  useBlockProps,
  BlockIcon,
} from "@wordpress/block-editor";

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

export default function Edit(props) {
  const { attributes, setAttributes } = props;
  const { src, alt, prevSearchTerm } = attributes;
  const [loading, setLoading] = useState(false);
  const [apiData, setApiResultData] = useState(null);
  const [searchTerm, setSearchTerm] = useState("");
  const [currentPage, setCurrentPage] = useState(1);

  const [activeSource, setActiveSource] = useState([]);
  const [sources, setsources] = useState([]);
  const [searchAllSources, setSearchAllSources] = useState(true);
  const [showSearch, setshowSearch] = useState(true);

  const searchPhotos = async (e, page) => {
    e.preventDefault();
    setLoading(true);
    fetch(
      `https://api.openverse.engineering/v1/images?format=json&shouldPersistImages=true&q=${
        searchTerm ? searchTerm : prevSearchTerm
      }&source=${
        activeSource ? activeSource : ""
      }&licence=BY-NC-SA&page_size=20&page=${page ? page : 1}`
    )
      .then((response) => response.json())
      .then((data) => {
        setApiResultData(data);
      })
	   .then(() => { setLoading(false); setshowSearch(true); })
      .catch((error) => {});
  };

  //get sources from the openverse api
  const getSources = async (e) => {
    setLoading(true);
    fetch(`https://api.openverse.engineering/v1/images/stats/?format=json`)
      .then((response) => response.json())
      .then((data) => {
        setsources(data);
      })
      .then(() => setLoading(false))
      .catch((error) => {});
  };

  return (
    <div {...useBlockProps()}>
      {showSearch && (
        <Placeholder
          className="bg-yellow"
          icon={<BlockIcon icon="format-image" />}
          label={__("CC-licensed images", "ls-wp-ccsearch")}
          instructions={__(
            "Quickly add openverce images in your site.",
            "ls-wp-ccsearch"
          )}
        >
          <CheckboxControl
            label="Get images from all available sources"
            help="Uncheck to select a provider from the list"
            checked={searchAllSources}
            onChange={(e) => {
              setSearchAllSources(!searchAllSources);
              searchAllSources ? getSources() : null;
            }}
          />
          {sources && !searchAllSources && (
            <SelectControl onChange={setActiveSource}>
              <option value="">
                {__("Search all sources", "ls-wp-ccsearch")}
              </option>
              {sources.map((provider) => (
                <option
                  value={provider.source_name}
                  selected={
                    activeSource === provider.source_name ? true : false
                  }
                >
                  {provider.display_name} ({provider.media_count})
                </option>
              ))}
            </SelectControl>
          )}
          <TextControl
            label="Keyword"
            value={searchTerm}
            onChange={(value) => setSearchTerm(value)}
          />
          <Button onClick={searchPhotos}>Search</Button>
        </Placeholder>
      )}
      <div className="openverse-search-results">
        {loading ? "Loading..." : ""}
        {showSearch && apiData && (
          <>
            {apiData.results && (
              <Grid>
                {apiData.results.map((image) => (
                  <figure>
                    <img
                      className="openverse-image"
                      alt={`${image.title} by ${image.provider} - ${image.license}`}
                      src={image.thumbnail}
                      onClick={() => {
                        setAttributes({
                          src: image.url,
                          alt: `${image.title} by ${image.provider} - ${image.license}`,
                          prevSearchTerm: searchTerm,
                        });
                        setshowSearch(false);
                      }}
                    ></img>
                    <figcaption>{`${image.title} by ${image.provider} - ${image.license}`}</figcaption>
                  </figure>
                ))}
              </Grid>
            )}
            {currentPage > 1 ? (
              <Button
                onClick={(e) => {
                  setCurrentPage(currentPage > 0 ? currentPage - 1 : 1);
                  searchPhotos(e, currentPage > 0 ? currentPage - 1 : 1);
                }}
              >
                Prev Page
              </Button>
            ) : null}
            <Button
              onClick={(e) => {
                setCurrentPage(currentPage + 1);
                searchPhotos(e, currentPage + 1);
              }}
            >
              Next Page
            </Button>
            {currentPage} / {apiData.page_count}
          </>
        )}
      </div>
      {!showSearch && src && (
        <>
          <figure>
            <img
              className="openverse-image"
              alt={alt}
              src={src}
              width="50%"
              height="50%"
              onClick={(e) => {
                searchPhotos(e, 1);
              }}
            ></img>
            <figcaption>{alt}</figcaption>
          </figure>
        </>
      )}
    </div>
  );
}