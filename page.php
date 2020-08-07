<?php get_header(); ?>

<main role="main">

    <div class="background">
        <div class="background-image"></div>
        <div class="background-gradient-sizer">
            <div class="background-gradient"></div>
        </div>
        <div class="background-solid"></div>
    </div>

    <div style="height:40px;"></div>

    <section class="section main-content">
        <div class="grid-x">
            <div class="cell small-12 medium-12 large-6 editor-content">
                <h1><?php the_title(); ?></h1>

                <div class="editor-content-body">
					<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

							<?php the_content(); ?>

							<?php comments_template( '', true ); ?>

                            <br class="clear">

							<?php edit_post_link(); ?>

                        </article>

					<?php endwhile; ?>

					<?php endif; ?>

                    <a class="button participate-cta">Osallistu CTA</a>

                </div>
            </div>
            <div class="cell large-6 main-content-image">
                <img src="<?php echo( get_template_directory_uri() . '/img/image.jpg' ); ?>" alt="Kilpailu"/>
            </div>
        </div>
    </section>

    <section class="section entries">
        <h2>Kilpailun kuvat otsikko</h2>
        <span class="section-description">Aikaa osallistua ja äänestää 1.2.2020 asti</span>
        <div style="height:70px;"></div>
        <p class="entries-no-entries" style="display:none;">Ei vielä kuvia. Ole ensimmäinen osallistuja!</p>
        <div class="grid-x entries-grid">
        </div>

        <div class="grid-x align-middle align-center entry-navigation">
            <div class="cell shrink entry-navigation-previous">
                Edellinen
            </div>
            <div class="cell shrink entry-navigation-numbers">

            </div>
            <div class="cell shrink entry-navigation-next">
                Seuraava
            </div>
        </div>
    </section>

    <section class="section participate">

        <div class="grid-x">
            <div class="cell medium-12 large-6 participate-body">
                <h2>Osallistu kilpailuun!</h2>
                <div style="height:40px;"></div>
                <p class="small">
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Sed posuere interdum sem.
                    Quisque ligula eros ullamcorper quis, lacinia quis facilisis sed sapien.
                <p>
                <p class="small">
                    Mauris varius diam vitae arcu. Sed arcu lectus auctor vitae, consectetuer et venenatis eget velit.
                    Sed augue orci, lacinia eu tincidunt et eleifend nec lacus. Donec ultricies nisl ut felis,
                    suspendisse potenti. Lorem ipsum ligula ut hendrerit mollis, ipsum erat vehicula risus, eu suscipit
                    sem libero nec erat. Aliquam erat volutpat.
                <p>
                <p class="small">
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Sed posuere interdum sem.
                    Quisque ligula
                    eros ullamcorper quis, lacinia quis facilisis sed sapien. </p>
            </div>
            <div class="cell medium-12 large-6 participate-postbox">
                <form id="participation-form" name="participation" method="post"
                      action="<?php echo( get_template_directory_uri() . "/api/insert_entry.php" ); ?>"
                      enctype="multipart/form-data" novalidate>

                    <p><label for="participation-title">Kuvan otsikko *</label>
                        <input type="text" id="participation-title" value="" tabindex="1" size="20" name="title"
                               required/>
                    </p>

                    <p><label for="participation-name">Nimi *</label>
                        <input type="text" id="participation-name" value="" tabindex="1" size="20" name="name"
                               required/>
                    </p>

                    <p><label for="participation-email">Sähköposti *</label>
                        <input type="email" id="participation-email" value="" tabindex="1" size="20" name="email"
                               required/>
                    </p>

                    <p>
                        <input type="file" name="image" id="participation-image" accept="image/*" required
                               style="display:none;">
                        <label class="participation-image-label" for="participation-image"><i
                                    class="participation-image-label-icon"></i> Valitse kuva</label>
                    </p>

                    <p><input type="checkbox" id="participation-checkbox" name="checkbox" value="checked" required>
                        <label class="participation-checkbox-label" for="participation-checkbox">Hyväksyn kilpailun <a
                                    href="#/">säännöt ja
                                ehdot</a></label></p>

                    <p class="participation-form-error"><i class="participation-form-error-icon"></i><span
                                class="participation-form-error-message">Error text</span>
                    </p>

                    <input type="submit" class="participation-submit" name="participation-submit" value="Osallistu"/>

                    <input type="hidden" name="action" value="new-participation"/>

                    <input type="hidden" name="is-ajax" value="0"/>

					<?php wp_nonce_field( 'participation-nonce' ); ?>
                </form>

            </div>
        </div>


    </section>

    <section class="section rules">
        <div class="grid-x">
            <div class="cell large-3 medium-4 small-12">
                <h3 style="color:#000;">Säännöt ja ehdot</h3>
                <div style="height:25px;"></div>
                <p style="font-size: 16px;line-height: 26.72px;">Kilpailuaika 1.1.2020-1.2.2020<br>Kilpailun järjestää
                    Kansallisteatteri<br>Lorem
                    ipsum dolor sit amet<br>Yms.</p>
            </div>
            <div class="cell large-6 medium-8 small-12 rules-body">
                <p class="x-small">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Sed posuere interdum sem.
                    Quisque ligula eros ullamcorper quis, lacinia quis facilisis sed sapien. Mauris varius diam vitae
                    arcu. Sed arcu lectus auctor vitae, consectetuer et venenatis eget velit. Sed augue orci, lacinia eu
                    tincidunt et eleifend nec lacus. Donec ultricies nisl ut felis, suspendisse potenti. Lorem ipsum
                    ligula ut hendrerit mollis, ipsum erat vehicula risus, eu suscipit sem libero nec erat. Aliquam erat
                    volutpat. Sed congue augue vitae neque. Nulla consectetuer porttitor pede. Fusce purus morbi tortor
                    magna condimentum vel, placerat id blandit sit amet tortor.</p>
                <p class="x-small">
                    Mauris sed libero. Suspendisse facilisis nulla in lacinia laoreet, lorem velit accumsan velit vel
                    mattis libero nisl et sem. Proin interdum maecenas massa turpis sagittis in, interdum non lobortis
                    vitae massa. Quisque purus lectus, posuere eget imperdiet nec sodales id arcu. Vestibulum elit pede
                    dictum eu, viverra non tincidunt eu ligula.</p>
                <p class="x-small">
                    Nam molestie nec tortor. Donec placerat leo sit amet velit. Vestibulum id justo ut vitae massa.
                    Proin in dolor mauris consequat aliquam. Donec ipsum, vestibulum ullamcorper venenatis augue.
                    Aliquam tempus nisi in auctor vulputate, erat felis pellentesque augue nec, pellentesque lectus
                    justo nec erat. Aliquam et nisl. Quisque sit amet dolor in justo pretium condimentum.</p>
                <p class="x-small">
                    Vivamus placerat lacus vel vehicula scelerisque, dui enim adipiscing lacus sit amet sagittis, libero
                    enim vitae mi. In neque magna posuere, euismod ac tincidunt tempor est. Ut suscipit nisi eu purus.
                    Proin ut pede mauris eget ipsum. Integer vel quam nunc commodo consequat. Integer ac eros eu tellus
                    dignissim viverra. Maecenas erat aliquam erat volutpat. Ut venenatis ipsum quis turpis. Integer
                    cursus scelerisque lorem. Sed nec mauris id quam blandit consequat. Cras nibh mi hendrerit vitae,
                    dapibus et aliquam et magna. Nulla vitae elit. Mauris consectetuer odio vitae augue.</p>
            </div>

        </div>
    </section>

</main>

<?php get_footer(); ?>
