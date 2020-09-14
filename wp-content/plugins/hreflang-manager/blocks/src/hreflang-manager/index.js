//Stylesheet
import './editor.css';

//Dependencies
const {__} = wp.i18n;
const {registerPlugin} = wp.plugins;
const {PluginSidebar} = wp.editPost;
const {TextControl, SelectControl, Button, Modal} = wp.components;
const {Component} = wp.element;
const {select, dispatch, registerStore} = wp.data;
const {withState} = wp.compose;

//Import
import utility from './utility.js';
import languages from './languages.js';
import locale from './locale.js';

class Hreflang_Manager extends Component {

  constructor(props) {

    super(...arguments);

    let updateObject = {};
    for (let i = 1; i <= DAHM_OPTIONS.connectionsInMenu; i++) {

      //Prepare the values of URLs, languages and locale in the state
      updateObject = {
        ...updateObject,
        ['url' + i]: '',
        ['language' + i]: 'en',
        ['locale' + i]: '',
      };

    }

    this.state = {
      ...updateObject,
    };

    //Add the 'Not Assigned' value to the locale array
    locale.unshift([__('Not Assigned', 'dahm'), '']);

    //The list of languages used in Select is generate here only one time for performance reasons
    this.languagesOptions = languages.map(
        (value) => {
          return {
            value: value[1],
            label: value[1] + ' - ' + value[0],
          };
        });

    //The list of locale used in Select is generate here only one time for performance reasons
    this.localeOptions = locale.map(
        (value) => {
          return {
            value: value[1],
            label: value[1].length > 0 ? value[1] + ' - ' + value[0] : value[0],
          };
        });

    //Redux Store START ----------------------------------------------------------------------------------------------------

    //First, lets give the "shape" of the store in the initial state object:
    var initialState = {};
    for (let i = 1; i <= DAHM_OPTIONS.connectionsInMenu; i++) {
      initialState = {
        connections: {
          ...initialState.connections,
          ['url' + i]: '',
          ['language' + i]: 'en',
          ['locale' + i]: '',
        },
      };
    }

    //The reducer used to modify the state of the store based on the provided action type and value
    const reducer = (state = initialState, action) => {
      switch (action.type) {

        case 'UPDATE': {
          return {
            ...state,
            connections: {
              ...state.connections,
              ...action.value,
            },
          };
        }

      }

      return state;
    };

    //The actions of the store used to update the data with 'dispatch'
    const actions = {

      //Update the store by sending the "UPDATE" type along with the connection data to the reducer
      update(value) {
        return {
          type: 'UPDATE',
          value: value,
        };
      },

    };

    //The selectors of the store used to retrieve the data with 'select'
    const selectors = {

      //Get all the connection data from the store
      getConnectionData(state) {
        return state.connections;
      },

    };

    //Register the store
    registerStore('hreflang_manager/main_store', {
      reducer,
      actions,
      selectors,
    });

    //Redux Store END ------------------------------------------------------------------------------------------------------

    //Subscribe START --------------------------------------------------------------------------------------------------

    /**
     * Here a subscription is required to detect when the post is saved.
     *
     * When the post is saved use the hreflang-manager endpoint of the Rest API to save the values
     * available in the modal windows (the values of the state of the ConnectionModalWindow component) in the
     * connections database table.
     *
     * (the proper endpoint used to save the data should is registered in PHP)
     *
     */
    var lastModified = '';

    wp.data.subscribe(() => {

      var postId = wp.data.select('core/editor').getCurrentPost().id;
      var postModifiedIsChanged = false;

      if (typeof wp.data.select('core/editor').getCurrentPost().modified !== 'undefined' &&
          wp.data.select('core/editor').getCurrentPost().modified !== lastModified) {
        lastModified = wp.data.select('core/editor').getCurrentPost().modified;
        postModifiedIsChanged = true;
      }

      /**
       * Update the connection data when:
       *
       * - The post has been saved
       * - This is not an not an autosave
       * - The "lastModified" flag used to detect if the post "modified" date has changed is set to true
       */
      if (wp.data.select('core/editor').isSavingPost() &&
          !wp.data.select('core/editor').isAutosavingPost() &&
          postModifiedIsChanged === true
      ) {

        //get the value
        const connectionData = select('hreflang_manager/main_store').getConnectionData();

        /**
         * Here the following tasks are performed:
         *
         * - Save the connection data with the Rest API
         * - Update the state of the modal window
         * - Update the values in the store
         */
        const postId = wp.data.select('core/editor').getCurrentPost().id;
        wp.apiFetch({
          path: '/daext-hreflang-manager/v1/post/',
          method: 'POST',
          body: JSON.stringify({
            postId: postId,
            connectionData: connectionData,
          }),
        }).then(
            () => {

              //Set the values of URLs, languages and locale in the state and in the store
              let updateObject = {};
              for (let i = 1; i <= DAHM_OPTIONS.connectionsInMenu; i++) {
                updateObject = {
                  ...updateObject,
                  ['url' + i]: connectionData['url' + i],
                  ['language' + i]: connectionData['language' + i],
                  ['locale' + i]: connectionData['locale' + i],
                };
              }
              this.setState(updateObject);
              dispatch('hreflang_manager/main_store').update(updateObject);

            },
            (err) => {

              return err;

            },
        );

      }

    });

    //Subscribe END ----------------------------------------------------------------------------------------------------

  }

  /**
   * This method is invoked immediately after a component is mounted (inserted
   * into the tree). Initializations that requires DOM nodes should go here. If
   * you need to load data from a remote endpoint, this is a good place to
   * instantiate the network requests.
   *
   * https://reactjs.org/docs/react-component.html#componentdidmount
   */
  componentDidMount() {

    /**
     * Set the value of the connection modal window by retrieving the hreflang data from the database. If there
     * isn't a record associated with this post retrieve the hreflang data from the plugin options.
     */
    const postId = wp.data.select('core/editor').getCurrentPost().id;
    wp.apiFetch({
      path: '/daext-hreflang-manager/v1/post/' + postId,
      method: 'GET',
    }).then(
        (databaseData) => {

          if (databaseData !== false) {

            /**
             * Set the values of URLs, languages and locale in the state and in the store by using the record
             * stored in the database.
             */
            let updateObject = {};
            for (let i = 1; i <= DAHM_OPTIONS.connectionsInMenu; i++) {
              updateObject = {
                ...updateObject,
                ['url' + i]: databaseData['url' + i],
                ['language' + i]: databaseData['language' + i],
                ['locale' + i]: databaseData['locale' + i],
              };
            }
            this.setState(updateObject);
            dispatch('hreflang_manager/main_store').update(updateObject);

          } else {

            /**
             * Set the values of URLs, languages and locale in the state and in the store by using the plugin
             * options available in the "Defaults" tab.
             */
            wp.apiFetch({
              path: '/daext-hreflang-manager/v1/options/',
              method: 'GET',
            }).then(
                (optionsData) => {

                  /**
                   * Set the values of URLs, languages and locale in the state and in the store by using the default
                   * values available in the options.
                   */
                  let updateObject = {};
                  for (let i = 1; i <= DAHM_OPTIONS.connectionsInMenu; i++) {
                    updateObject = {
                      ...updateObject,
                      ['url' + i]: '',
                      ['language' + i]: optionsData['da_hm_default_language_' + i],
                      ['locale' + i]: optionsData['da_hm_default_locale_' + i],
                    };
                  }
                  this.setState(updateObject);
                  dispatch('hreflang_manager/main_store').update(updateObject);

                },
                (err) => {

                  return err;

                },
            );

          }

        },
        (err) => {

          return err;

        },
    );

  }

  render() {

    //Prepare the data that should be passed to withState() as props
    let callingArray = [];
    let connectionObject = {};
    for (let i = 1; i <= DAHM_OPTIONS.connectionsInMenu; i++) {
      callingArray.push(i);
      connectionObject = {
        ...connectionObject,
        ['url' + i]: this.state['url' + i],
        ['language' + i]: this.state['language' + i],
        ['locale' + i]: this.state['locale' + i],
      };
    }

    const ConnectionModalWindow = withState({
      localeOptions: this.localeOptions,
      languagesOptions: this.languagesOptions,
      connection: connectionObject,
      callingArray: callingArray,
      isOpen: false,
    })(({
          localeOptions,
          languagesOptions,
          connection,
          callingArray,
          isOpen,
          setState,
        }) => (
        <div>
          <Button className='dahm-set-connection' isDefault onClick={() => setState({isOpen: true})}>

            <div className='dahm-set-connection-container'>
              <div className='dahm-set-connection-text'>{__('Edit Connection', 'dahm')}</div>
              <span className="dashicons dashicons-edit dahm-set-connection-icon"></span>
            </div>
          </Button>
          {isOpen ?
              <Modal
                  title={__('Edit Connection', 'dahm')}
                  onRequestClose={() => {
                    setState({isOpen: false});
                  }}
                  className='dahm-modal'
              >
                {callingArray.map((index) =>
                    <div className='dahm-single-connection'>
                      <TextControl
                          autocomplete='off'
                          label={__('URL', 'dahm') + String.fromCharCode(160) + index}
                          value={connection['url' + index]}
                          onChange={(value) => {
                            setState({
                              connection: {
                                ...connection,
                                ['url' + index]: value,
                              },
                            });
                            dispatch('hreflang_manager/main_store').update({['url' + index]: value});
                            utility.activateUpdateButton();
                          }}
                      />
                      <SelectControl
                          label={__('Language', 'dahm') + String.fromCharCode(160) + index}
                          value={connection['language' + index]}
                          onChange={(value) => {
                            setState({
                              connection: {
                                ...connection,
                                ['language' + index]: value,
                              },
                            });
                            dispatch('hreflang_manager/main_store').update({['language' + index]: value});
                            utility.activateUpdateButton();
                          }}
                          options={languagesOptions}
                      />
                      <SelectControl
                          label={__('Locale', 'dahm') + String.fromCharCode(160) + index}
                          value={connection['locale' + index]}
                          onChange={(value) => {
                            setState({
                              connection: {
                                ...connection,
                                ['locale' + index]: value,
                              },
                            });
                            dispatch('hreflang_manager/main_store').update({['locale' + index]: value});
                            utility.activateUpdateButton();
                          }}
                          options={localeOptions}
                      />
                    </div>,
                )}
              </Modal>
              : null}
        </div>
    ));

    return (
        <PluginSidebar
            name='hreflang-manager-sidebar'
            icon='admin-site'
            title={__('Hreflang Manager', 'dahm')}
        >
          <div
              className='hreflang-manager-sidebar-content'
          >
            <ConnectionModalWindow/>
          </div>
        </PluginSidebar>
    );

  }

}

registerPlugin('dahm-hreflang-manager', {
  render: Hreflang_Manager,
});