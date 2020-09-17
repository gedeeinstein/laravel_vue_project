<script type="text/x-template" id="project-entry">
    <div>

        <!-- Default screen layout - Start -->
        <div class="project-entry d-print-none mt-4">
    
            <!-- Project header - Start -->
            @relativeInclude("includes.screen.header")
            <!-- Project header - End -->
    
            <!-- Project content - Start -->
            @relativeInclude("includes.screen.content")
            <!-- Project content - End -->
    
            <!-- Project detail - Start -->
            @relativeInclude("includes.screen.detail")
            <!-- Project detail - End -->
    
            <!-- Project controls - Start -->
            @relativeInclude("includes.screen.controls")
            <!-- Project controls - End -->

        </div>
        <!-- Default screen layout - End -->

        <!-- Print layout - Start -->
        <div class="project-entry d-none d-print-block mt-5">
    
            <!-- Project header - Start -->
            @relativeInclude("includes.print.header")
            <!-- Project header - End -->
    
            <!-- Project content - Start -->
            @relativeInclude("includes.print.content")
            <!-- Project content - End -->
    
            <!-- Project detail - Start -->
            @relativeInclude("includes.print.detail")
            <!-- Project detail - End -->
    
            <!-- Project controls - Start -->
            @relativeInclude("includes.print.controls")
            <!-- Project controls - End -->
                
        </div>
        <!-- Print layout - End -->
    </div>
</script>