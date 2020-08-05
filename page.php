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
            <div class="cell small-6 editor-content">
                <h1><?php the_title(); ?></h1>

                <div class="editor-content-body">
					<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>

                        <!-- article -->
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

							<?php the_content(); ?>

							<?php comments_template( '', true ); // Remove if you don't want comments ?>

                            <br class="clear">

							<?php edit_post_link(); ?>

                        </article>
                        <!-- /article -->

					<?php endwhile; ?>

					<?php else: ?>

                        <!-- article -->
                        <article>

                            <h2><?php _e( 'Sorry, nothing to display.', 'kilpailusivu' ); ?></h2>

                        </article>
                        <!-- /article -->

					<?php endif; ?>
                </div>
            </div>
            <div class="cell small-6 main-content-image">
                <img src="<?php echo( get_template_directory_uri() . '/img/image.jpg' ); ?>" alt="Kilpailu"/>
            </div>
        </div>
    </section>

    <section class="section images">
        <h2>Kilpailun kuvat otsikko</h2>
        <span class="section-description">Aikaa osallistua ja äänestää 1.2.2020 asti</span>
    </section>

    <section class="section participate">

        <div class="grid-x">
            <div class="cell small-6">
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
            <div class="cell small-6 participate-postbox">
                <form id="participation-form" name="participation" method="post" action="" enctype="multipart/form-data">

                    <p><label for="participation-title">Kuvan otsikko</label><br/>
                        <input type="text" id="participation-title" value="" tabindex="1" size="20" name="title"/>
                    </p>

                    <p><label for="participation-name">Nimi</label><br/>
                        <input type="text" id="participation-name" value="" tabindex="1" size="20" name="name"/>
                    </p>

                    <p><label for="participation-email">Sähköposti</label><br/>
                        <input type="text" id="participation-email" value="" tabindex="1" size="20" name="email"/>
                    </p>

                    <p><label for="participation-image">Valitse kuva</label><br/>
                        <input type="file" name="image" id="participation-image" accept="image/*"
                               style="display:none;">
                    </p>

                    <p><input type="checkbox" id="participation-checkbox" name="checkbox" value="">
                        <label for="participation-checkbox">Hyväksyn kilpailun <a href="#">säännöt ja
                                ehdot</a></label><br></p>

                    <input type="submit" name="participation-submit" value="Osallistu"/>

                    <input type="hidden" name="action" value="new-participation"

					<?php wp_nonce_field( 'participation-nonce' ); ?>
                </form>
            </div>
        </div>


    </section>

    <section class="section rules">

    </section>

</main>

<?php get_footer(); ?>
