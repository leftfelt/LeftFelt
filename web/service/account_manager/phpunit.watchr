def testrun(cmd)
	cmd = "phpunit #{cmd}"
	puts "$ #{cmd}"
	system cmd
end
def phprun(cmd)
	cmd = "php #{cmd}"
	puts "$ #{cmd}"
	system cmd
end

watch("^test/(.*)Test.class.php") { |m| testrun "test/#{m[1]}Test.class.php" }
watch("^lib/logic/(.*)Logic.class.php") { |m| testrun "test/logic/#{m[1]}LogicTest.class.php" }
watch("^lib/model/(.*)Model.class.php") { |m| testrun "test/model/#{m[1]}ModelTest.class.php" }
watch("^lib/renderer/(.*)Renderer.class.php") { |m| testrun "test/renderer/#{m[1]}RendererTest.class.php" }
watch("^lib/filter/(.*)Filter.class.php") { |m| testrun "test/filter/#{m[1]}FilterTest.class.php" }
watch("^public_html/(.*).php") { |m| phprun "public_html/#{m[1]}.php" }
